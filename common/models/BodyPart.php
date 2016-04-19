<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "body_part".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 */
class BodyPart extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'body_part';
    }

    public function beforeDelete()
    {
        if (!empty($this->inquiry)) {
            Yii::$app->session->setFlash('alert', [
                'options' => ['class'=>'alert-danger'],
                'body' => Yii::t('app', 'You can`t delete this item because it has inquiries')
            ]);
            return false;
        }
        return parent::beforeDelete();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 16],
            [['name'], 'required'],
            [['description'], 'string', 'max' => 128],
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
            'description' => Yii::t('app', 'Description'),
        ];
    }

    public function getBodyTreatment()
    {
        return $this->hasMany(BodyTreatment::className(), ['body_part_id' => 'id']);
    }

    public function getInquiry()
    {
        return $this->hasOne(Inquiry::className(), ['body_part_id' => 'id']);
    }
}
