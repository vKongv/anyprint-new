<?php

namespace common\models\print_request;

use Yii;

/**
 * This is the model class for table "print_request".
 *
 * @property integer $id
 * @property string $job_id
 * @property string $name
 * @property string $status
 * @property string $created_at
 * @property string $price
 * @property integer $verification_code
 * @property integer $printer_id
 * @property integer $user_id
 *
 * @property Printer $printer
 * @property User $user
 */
class PrintRequest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'print_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'job_id', 'name', 'status', 'verification_code', 'printer_id', 'user_id'], 'required'],
            [['id', 'verification_code', 'printer_id', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['price'], 'number'],
            [['job_id'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 32],
            [['printer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Printer::className(), 'targetAttribute' => ['printer_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Print Request ID'),
            'job_id' => Yii::t('app', 'Job ID in GCP'),
            'name' => Yii::t('app', 'Name of the document'),
            'status' => Yii::t('app', 'Print Request Status'),
            'created_at' => Yii::t('app', 'Print Request Submit Time'),
            'price' => Yii::t('app', 'Price of the print request'),
            'verification_code' => Yii::t('app', 'Verification Code for the print request'),
            'printer_id' => Yii::t('app', 'Printer ID that receiving the job'),
            'user_id' => Yii::t('app', 'User ID that submitting the job'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrinter()
    {
        return $this->hasOne(Printer::className(), ['id' => 'printer_id']);
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
     * @return PrintRequestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PrintRequestQuery(get_called_class());
    }
}
