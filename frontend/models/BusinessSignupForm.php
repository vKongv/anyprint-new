<?php
namespace frontend\models;

use common\models\shop\Shop;
use yii\base\Model;
use common\models\user\User;
use Yii;

/**
 * Signup form
 */
class BusinessSignUpForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $repeat_password;
    public $hp;
    public $shop_name;
    public $area;
    public $address;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //TODO: Allow only [A-Za-z0-9_-] for name
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\user\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 20],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\user\User', 'message' => 'This email address has already been taken.'],

            ['hp', 'string', 'min' => 9, 'max' => 11],
            ['hp', 'required'],
            ['hp', 'unique', 'targetClass' => '\common\models\user\User', 'message' => 'This handphone number already register in our system.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 8, 'max' => 255],
            ['repeat_password', 'required'],
            ['repeat_password', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match" ],


            ['shop_name', 'required'],
            ['shop_name', 'string', 'min' => 8, 'max' => 127],

            ['area', 'required'],
            ['area', 'string'],

            ['address', 'required'],
            ['address', 'string', 'min' => 16, 'max' => 255],
        ];
    }

    public function attributeLabels()
    {

        return [
            'username' => 'Username',
            'email' => 'Your email address',
            'password' => 'Your password',
            'repeatPassword' => 'Retype your password',
            'hp' => 'Your handphone number',
            'shop_name' => 'Your shop name',
            'address' => 'Your shop address',
            'area' => 'Your shop location (state)'
        ];
    }

    /**
     * Signs user up.
     * @return User|null the saved model or null if saving fails
     * @throws \Exception
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        //Create a transaction for this action
        $connection = Yii::$app->getDb();
        $transaction = $connection->beginTransaction();
        try {
            //Register user
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->hp = $this->hp;
            $user->type = User::TYPE_BUSINESS;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user_saved = $user->save();

            //Register shop
            $shop = new Shop();
            $shop->name = $this->shop_name;
            $shop->area = $this->area;
            $shop->address = $this->address;
            $shop->generateVerificationCode();
            $shop->user_id = $user->id;
            $shop_saved = $shop->save();

            //Commit the transaction
            if($shop_saved && $user_saved) {

                $transaction->commit();
                return $user->save() ? $user : null;
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            throw $e;
        }
    }
}
