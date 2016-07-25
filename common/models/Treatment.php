<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\components\StatusHelper;
use trntv\filekit\behaviors\UploadBehavior;

/**
 * This is the model class for table "{{%treatment}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $sub_string
 * @property string $instruction
 * @property string $app_name
 * @property integer $created_at
 * @property integer $status
 * @property integer $param_multiselect
 * @property integer $per_item
 * @property string $icon_base_url
 * @property string $icon_path
 * @property integer $select_both_button
 * @property integer $buttons_in_row
 * @property integer $session_buttons_position
 * @property integer $reg_description
 *
 * @property TreatmentSession[] $treatmentSessions
 * @property BodyPartTreatment[] $bodyPartTreatments
 * @property Brand[] $brands
 * @property TreatmentParam[] $treatmentParams
 * @property TreatmentIntensity[] $treatmentIntensity
 */
class Treatment extends \yii\db\ActiveRecord
{
    public $per_session;
    public $uploaded_image;
    public $intensity;
    public $is_brand_provided;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%treatment}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ]
            ],
//            'uploaded_image' => [
//                'class' => UploadBehavior::className(),
//                'attribute' => 'uploaded_image',
//                'pathAttribute' => 'icon_path',
//                'baseUrlAttribute' => 'icon_base_url'
//            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            [['sub_string', 'reg_description', 'instruction', 'app_name'], 'string'],
            ['name', 'required'],
            [['created_at', 'status', 'param_multiselect', 'per_item', 'per_session', 'select_both_button', 'buttons_in_row', 'session_buttons_position'], 'integer'],
            ['name', 'string', 'max' => 255],
            ['status', 'in', 'range' => [StatusHelper::STATUS_ACTIVE, StatusHelper::STATUS_NOT_ACTIVE]],
            ['status','default', 'value' => StatusHelper::STATUS_ACTIVE],
            ['uploaded_image', 'safe'],
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
            'sub_string' => Yii::t('app', 'Sub String'),
            'created_at' => Yii::t('app', 'Created at'),
            'status' => Yii::t('app', 'Status (Active/Not active)'),
            'per_item' => Yii::t('app', 'Per item'),
            'per_session' => Yii::t('app', 'Per session'),
            'instruction' => Yii::t('app', 'Instruction'),
            'select_both_button' => Yii::t('app', 'Add select both button'),
            'session_buttons_position' => Yii::t('app', 'If checked session buttons will be positioned above params'),
            'buttons_in_row' => Yii::t('app', 'Define button quantity in a row'),
            'intensity' => Yii::t('app', 'Check if intensity needed'),
            'is_brand_provided' => Yii::t('app', 'If checked you will have to fill brands for every brand param'),
            'reg_description' => Yii::t('app', 'Description while doctor registration'),
            'app_name' => Yii::t('app', 'Application Name'),
        ];
    }

    public function beforeDelete()
    {
        foreach ($this->treatmentParams as $param) {
            if($param->inquiryTreatments) {
                Yii::$app->session->setFlash('alert', [
                    'options' => ['class'=>'alert-danger'],
                    'body' => Yii::t('app', 'You can`t delete this treatment because it has inquiries')
                ]);
                return false;
            }
        }
        return parent::beforeDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalAttributes()
    {
        return $this->hasMany(AdditionalAttributeItem::className(), ['treatment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTreatmentIntensity()
    {
        return $this->hasMany(TreatmentIntensity::className(), ['treatment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoParams()
    {
        return $this->hasMany(PhotoParam::className(), ['item_id' => 'id']);
    }

    public function getDefaultSession()
    {
        return $this->hasOne(TreatmentSession::className(), ['treatment_id' => 'id'])->orderBy('session_count ASC')->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrands()
    {
        return $this->hasMany(Brand::className(), ['treatment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTreatmentParams()
    {
        return $this->hasMany(TreatmentParam::className(), ['treatment_id' => 'id']);
    }

    /**
     * @return $this
     */
    public function getActiveTreatmentParams()
    {
        return $this->hasMany(TreatmentParam::className(), ['treatment_id' => 'id'])->where(['status' =>true]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTreatmentSessions()
    {
        return $this->hasMany(TreatmentSession::className(), ['treatment_id' => 'id']);
    }

    public static function getTreatmentName($id)
    {
        $treatment = self::find()->where(['id' => $id])->one();
        return $treatment ? $treatment->name : Yii::t('app', 'Standalone');
    }

    public static function getPhoto($base_url, $path)
    {
        return ($base_url && $path)
            ? Yii::getAlias($base_url . '/' . $path)
            : Yii::getAlias('@backendUrl'. '/img/default_treatment.jpg');
    }
}


