<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invoice_generation".
 *
 * @property integer $id
 * @property integer $created_at
 *
 * @property Invoice $doctorInvoices
 */
class InvoiceGeneration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice_generation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Invoice generation date'),
        ];
    }

    /**
     * @return $this
     */
    public function getDoctorInvoices()
    {
        return $this->hasOne(Invoice::className(), ['date_id' => 'id'])->where(['invoice.user_id' => Yii::$app->user->id]);
    }
}
