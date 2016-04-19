<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "registration_invite".
 *
 * @property integer $id
 * @property integer $bidder_id
 * @property integer $promo_id
 * @property integer $status
 *
 * @property User $bidder
 */
class RegistrationInvite extends \yii\db\ActiveRecord
{
    const IS_PENDING = 0;
    const IS_COMPLETED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'registration_invite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bidder_id', 'promo_id', 'status'], 'required'],
            [['bidder_id', 'promo_id', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bidder_id' => Yii::t('app', 'Bidder ID'),
            'promo_id' => Yii::t('app', 'Promo ID'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public function getBidder()
    {
        return $this->hasOne(User::className(),['id'=>'bidder_id']);
    }
}
