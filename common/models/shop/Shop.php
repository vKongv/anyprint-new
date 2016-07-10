<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "printing_shop".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $area
 * @property string $operating_hour
 * @property string $closing_hour
 * @property string $verification_code
 * @property integer $user_id
 *
 * @property Printer[] $printers
 * @property User $user
 */
class Shop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'printing_shop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'address', 'area', 'operating_hour', 'closing_hour', 'verification_code', 'user_id'], 'required'],
            [['operating_hour', 'closing_hour'], 'safe'],
            [['user_id'], 'integer'],
            [['name', 'address'], 'string', 'max' => 255],
            [['area'], 'string', 'max' => 64],
            [['verification_code'], 'string', 'max' => 4],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Printing Shop ID'),
            'name' => Yii::t('app', 'Printing Shop Name'),
            'address' => Yii::t('app', 'Printing Shop Actual Address'),
            'area' => Yii::t('app', 'State of the printing shop located'),
            'operating_hour' => Yii::t('app', 'Printing Shop Open Time'),
            'closing_hour' => Yii::t('app', 'Printing Shop Close Time'),
            'verification_code' => Yii::t('app', 'Verification code for printing shop'),
            'user_id' => Yii::t('app', 'User ID that own this shop'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrinters()
    {
        return $this->hasMany(Printer::className(), ['printin_shop_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return ShopQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShopQuery(get_called_class());
    }
}
