<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "add_attribute_item".
 *
 * @property integer $id
 * @property integer $treatment_id
 * @property string $value
 */
class AdditionalAttributeItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'add_attribute_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['treatment_id'], 'integer'],
            [['value'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'treatment_id' => Yii::t('app', 'Treatment Id'),
            'value' => Yii::t('app', 'Value'),
        ];
    }
}
