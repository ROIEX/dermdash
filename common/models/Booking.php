<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "booking".
 *
 * @property integer $id
 * @property integer $inquiry_id
 * @property integer $is_viewed
 * @property integer $is_viewed_admin
 * @property integer $created_at
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone_number
 * @property string $date
 *
 * @property Inquiry $inquiry
 */
class Booking extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'booking';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ]
        ];
    }

    public function beforeSave($insert)
    {
        $this->date = date("Y-m-d H:i:s",  strtotime($this->date));
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inquiry_id'], 'integer'],
            [['date'], 'safe'],
            [['is_viewed', 'is_viewed_admin'], 'default', 'value' => 0],
            [['first_name', 'last_name', 'email', 'phone_number'], 'string', 'max' => 255],
            [['inquiry_id'], 'exist', 'skipOnError' => true, 'targetClass' => Inquiry::className(), 'targetAttribute' => ['inquiry_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'inquiry_id' => Yii::t('app', 'Invoice #'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'email' => Yii::t('app', 'Email'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'date' => Yii::t('app', 'Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiry()
    {
        return $this->hasOne(Inquiry::className(), ['id' => 'inquiry_id']);
    }
    
    

    public static function countNewAppointments()
    {
        if (Yii::$app->user->can('administrator')) {
            $new_appointments = self::find()->where(['is_viewed_admin' => false])->count();
            
        } else {
            $booked_inquiriry_ids = ArrayHelper::getColumn(InquiryDoctorList::find()->where(['user_id' => Yii::$app->user->id])->all(), 'inquiry_id');
            $new_appointments = self::find()
                ->where(['is_viewed' => false])
                ->andWhere(['in', 'inquiry_id', $booked_inquiriry_ids])
                ->count();
        }
        
        return $new_appointments;
    }
}
