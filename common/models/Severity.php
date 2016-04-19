<?php

namespace common\models;

use common\components\StatusHelper;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "severity".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 *
 * @property TreatmentParamSeverity[] $params
 */
class Severity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'severity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            ['status', 'in', 'range' => [StatusHelper::STATUS_ACTIVE, StatusHelper::STATUS_NOT_ACTIVE]],
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
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public static function getActiveSeverities()
    {
        $severity_array =  self::find()->where(['status' => StatusHelper::STATUS_ACTIVE])->all();
        return ArrayHelper::map($severity_array, 'id', 'name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParams()
    {
        return $this->hasMany(TreatmentParamSeverity::className(),['severity_id'=>'id']);
    }
}
