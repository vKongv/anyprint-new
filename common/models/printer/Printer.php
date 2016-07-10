<?php

namespace common\models\printer;

use Yii;

/**
 * This is the model class for table "printer".
 *
 * @property integer $id
 * @property string $g_id
 * @property string $name
 * @property integer $status
 * @property integer $color_option
 * @property integer $deleted
 * @property integer $printin_shop_id
 *
 * @property PrintRequest[] $printRequests
 * @property PrintingShop $printinShop
 */
class Printer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'printer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['g_id', 'name', 'status', 'color_option', 'printin_shop_id'], 'required'],
            [['status', 'color_option', 'deleted', 'printin_shop_id'], 'integer'],
            [['g_id'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 255],
            [['g_id'], 'unique'],
            [['printin_shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => PrintingShop::className(), 'targetAttribute' => ['printin_shop_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Printer ID'),
            'g_id' => Yii::t('app', 'Identification number for printer from GCP'),
            'name' => Yii::t('app', 'Printer Name'),
            'status' => Yii::t('app', 'Printer\'s Status'),
            'color_option' => Yii::t('app', 'Printer\'s color capability'),
            'deleted' => Yii::t('app', 'Delete Indicator for printer'),
            'printin_shop_id' => Yii::t('app', 'Printing Shop ID that own this printer'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrintRequests()
    {
        return $this->hasMany(PrintRequest::className(), ['printer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrintinShop()
    {
        return $this->hasOne(PrintingShop::className(), ['id' => 'printin_shop_id']);
    }

    /**
     * @inheritdoc
     * @return PrinterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PrinterQuery(get_called_class());
    }
}
