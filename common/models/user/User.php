<?php

namespace common\models\user;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $password
 * @property string $hp
 * @property string $email
 * @property integer $status
 * @property integer $type
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $google_refresh_token
 */
class User extends ActiveRecord  implements IdentityInterface
{
    //For @property status
    const STATUS_UNVERIFIED = -1; //Not verified user
    const STATUS_HP_VERIFIED = 0; //Partial verified user (Verified only hp)
    const STATUS_EMAIL_VERIFIED = 1; //Partial verified user (Verified only email)
    const STATUS_VERIFIED = 2; //Verified user (Verfied both email and hp)

    //For @property type
    const TYPE_NORMAL = 0; //Normal user (Printing user)
    const TYPE_BUSINESS = 1; //Business owner

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'hp', 'email'], 'required'],
            [['status', 'type', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['hp', 'google_refresh_token'], 'string', 'max' => 64],
            [['username', 'email', 'hp'], 'unique', 'targetAttribute' => ['username', 'email', 'hp'], 'message' => 'The combination of User Name, User Handphone and User Email has already been taken.'],
            ['status', 'default', 'value' => self::STATUS_UNVERIFIED],
            ['status', 'in', 'range' => [self::STATUS_UNVERIFIED, self::STATUS_VERIFIED]],
            ['type', 'default', 'value' => self::TYPE_NORMAL],
            ['type', 'in', 'range' => [self::TYPE_NORMAL, self::TYPE_BUSINESS]]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'User ID'),
            'username' => Yii::t('app', 'User Name'),
            'auth_key' => Yii::t('app', 'User Authentication Key'),
            'password_hash' => Yii::t('app', 'User Password Hash'),
            'password_reset_token' => Yii::t('app', 'User Password Reset Token'),
            'password' => Yii::t('app', 'User Password'),
            'hp' => Yii::t('app', 'User Handphone'),
            'email' => Yii::t('app', 'User Email'),
            'status' => Yii::t('app', 'User Status'),
            'type' => Yii::t('app', 'User Type'),
            'created_at' => Yii::t('app', 'Created Time'),
            'updated_at' => Yii::t('app', 'Updated Time'),
            'google_refresh_token' => Yii::t('app', '(Business) Google OAuth2.0 Refresh Token'),
        ];
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
