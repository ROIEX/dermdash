<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "state".
 *
 * @property integer $id
 * @property string $name
 * @property string $status
 * @property string $short_name
 *
 * @property UserProfile[] $userProfiles
 *
 */
class State extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_UNACTIVE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'state';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 16],
            [['short_name'], 'string', 'max' => 8],
            ['status', 'integer']
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
            'short_name' => Yii::t('app', 'Short Name'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfiles()
    {
        return $this->hasMany(UserProfile::className(), ['state_id' => 'id']);
    }

    /**
     * @param bool|false $active
     * @return array
     */
    public static function getStates($active = false)
    {
        if ($active) {
            return ArrayHelper::map(self::find()->where(['status' => self::STATUS_ACTIVE])->all(),'id','name');
        }
        return ArrayHelper::map(self::find()->all(),'id','name');
    }

    public static function getShortName($state_id)
    {
        return self::findOne($state_id)->short_name;
    }

    public static function getStateIdByShortName($short_name)
    {
        $state =  self::find()->where(['short_name' => $short_name])->one();
        if (!empty($state)) {
            return $state->id;
        }
        return false;
    }

}
