<?php
namespace frontend\models;

use yii\base\Model;
use common\models\user\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $repeat_password;
    public $hp;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\user\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\user\User', 'message' => 'This email address has already been taken.'],

            ['hp', 'string', 'min' => 9, 'max' => 11],
            ['hp', 'required'],
            ['hp', 'unique', 'targetClass' => '\common\models\user\User', 'message' => 'This handphone number already register in our system.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['repeat_password', 'required'],
            ['repeat_password', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match" ],
        ];
    }

    public function attributeLabels()
    {

        return [
            'username' => 'Username',
            'email' => 'Your email address',
            'password' => 'Your password',
            'repeat_password' => 'Retype your password',
            'hp' => 'Your handphone number'
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->hp = $this->hp;
        $user->type = User::TYPE_NORMAL;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        return $user->save() ? $user : null;
    }
}
