<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "body_treatment".
 *
 * @property integer $id
 * @property integer $body_part_id
 * @property integer $treatment_item_id
 */
class BodyTreatment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'body_treatment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['body_part_id', 'treatment_item_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'body_part_id' => Yii::t('app', 'Body Part ID'),
            'treatment_item_id' => Yii::t('app', 'Treatment Item ID'),
        ];
    }

    public function getBodyPart()
    {
        return $this->hasOne(BodyPart::className(), ['id' => 'body_part_id']);
    }

    public function getTreatmentItem()
    {
        return $this->hasOne(TreatmentItem::className(), ['id' => 'treatment_item_id']);
    }

    public function saveTreatmentBodyParts($treatment)
    {
        $treatment_body_list = [];
        foreach ($treatment->treatment_body as $part) {
            $treatment_body_list[] = [
                'value_id' => $part,
                'treatment_item_id' => $treatment->id,
            ];
        }

        return Yii::$app->db->createCommand()->batchInsert(self::tableName(), $this->activeAttributes(), $treatment_body_list)->execute();
    }

    public function updateBodyPartTreatments($model)
    {
        if (!empty($model->bodyParts)) {
            $this->deleteBodyPartTreatments($model->id);
        }
        if (!empty($model->treatment_body)) {
            $this->saveTreatmentBodyParts($model);
        }
    }

    public function deleteBodyPartTreatments($treatment_id)
    {
        self::deleteAll(['treatment_item_id' => $treatment_id]);
    }

    public static function getAssignedTreatmentsList($body_part_id)
    {
        $treatment_list =  self::find()->where(['body_part_id' => $body_part_id])->all();
        $treatment_array = [];
        if (!empty($treatment_list)) {
            foreach ($treatment_list as $treatment) {
                $treatment_array[] = $treatment->treatmentItem->name;
            }
            return implode(', ', $treatment_array);
        }
       return Yii::t('app', 'No treatments');
    }
}
