<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%email_template}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $body
 */
class EmailTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%email_template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['body'], 'string'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'body' => Yii::t('app', 'Body'),
        ];
    }
}
