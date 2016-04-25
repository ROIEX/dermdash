<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $id
 * @property string $number
 * @property integer $user_id
 * @property string $file_path
 * @property integer $date_id
 * @property integer $net_total
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'date_id', 'number'], 'integer'],
            ['file_path', 'string', 'max' => 128],
            ['net_total', 'double']
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'number' => Yii::t('app', 'Number'),
            'user_id' => Yii::t('app', 'User ID'),
            'file_path' => Yii::t('app', 'File Path'),
            'date_id' => Yii::t('app', 'Created Date'),
        ];
    }

    public function savePdf(array $pdf, $user_id, $generation_date_id, $net_total, $invoice_number = null)
    {
        $this->user_id = $user_id;
        $this->file_path = Yii::getAlias('@storageUrl/source/invoice/') . $pdf['filename'];
        $this->date_id = $generation_date_id;
        $this->net_total = round($net_total, 2);
        if ($invoice_number) {
            $this->number = $invoice_number;
        }

        if(!$this->save()) {
            return false;
        }

        return true;
    }
}
