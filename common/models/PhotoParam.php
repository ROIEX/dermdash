<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "photo_param".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $count
 * @property integer $body_part_id
 *
 * @property BodyPart $bodyPart
 * @property Item $item
 */
class PhotoParam extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'photo_param';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'body_part_id'], 'integer'],
            ['count', 'integer', 'min' => '1'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'item_id' => Yii::t('app', 'Item ID'),
            'count' => Yii::t('app', 'Count'),
            'body_part_id' => Yii::t('app', 'Body part'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBodyPart()
    {
        return $this->hasOne(BodyPart::className(), ['id' => 'body_part_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTreatment()
    {
        return $this->hasOne(Treatment::className(), ['id' => 'item_id']);
    }
}
