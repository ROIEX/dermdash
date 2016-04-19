<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_device".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $device_type
 * @property string $device_token
 * @property integer $created_at
 * @property integer $badge
 */
class UserDevice extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_device';
    }

    public function behaviors()
    {
        return [
            [
                'class'=>TimestampBehavior::className(),
                'createdAtAttribute'=>'created_at',
                'updatedAtAttribute'=>false
            ],
            [
                'class'=>BlameableBehavior::className(),
                'createdByAttribute'=>'user_id',
                'updatedByAttribute'=>false
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'device_type', 'created_at', 'badge'], 'integer'],
            [['device_token'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'device_type' => Yii::t('app', 'Device Type'),
            'device_token' => Yii::t('app', 'Device Token'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
}
