<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "promo_used".
 *
 * @property integer $id
 * @property integer $promo_id
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $used_while
 * @property integer $counted
 */
class PromoUsed extends \yii\db\ActiveRecord
{
    const USED_WHILE_REGISTRATION = 0;
    const USED_WHILE_PURCHASE = 1;

    const NOT_COUNTED = 0;
    const COUNTED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promo_used';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['promo_id', 'user_id', 'created_at', 'counted'], 'integer'],
            [['promo_id', 'user_id'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'promo_id' => Yii::t('app', 'Promo ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getPromoCode()
    {
        return $this->hasOne(PromoCode::className(), ['id' => 'promo_id']);
    }

    public static function getUsedPromocodeList($user_id)
    {
        return self::find()->where(['user_id' => $user_id])->all();
    }
}
