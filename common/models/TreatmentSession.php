<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "treatment_session".
 *
 * @property integer $id
 * @property integer $treatment_id
 * @property integer $session_count
 */
class TreatmentSession extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'treatment_session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['treatment_id', 'session_count'], 'integer'],
            ['session_count', 'required', 'when' => function() {
                return false;
            }, 'whenClient' => "function(attribute, value) {
                      return !$('#treatment-per_session').is(':checked');
                  }"],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'treatment_id' => Yii::t('app', 'Treatment ID'),
            'session_count' => Yii::t('app', 'Session Count'),
        ];
    }
}
