<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%treatment_param}}".
 *
 * @property integer $id
 * @property integer $treatment_id
 * @property string $value
 * @property string $status
 * @property string $icon_base_url
 * @property string $icon_path
 * @property string $reg_description
 *

 *
 * @property InquiryTreatment[] $inquiryTreatments
 * @property TreatmentParamSeverity[] $severity
 * @property Treatment $treatment
 * @property BrandProvidedTreatment $provided
 * @property TreatmentParamSeverity[] $filledSeverity
 * @property BrandProvidedTreatment[] $brandProvided
 */
class TreatmentParam extends \yii\db\ActiveRecord
{
    public $uploaded_image;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%treatment_param}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['treatment_id'], 'integer'],
            [['icon_base_url', 'icon_path', 'reg_description'], 'string'],
            [['value', 'status'], 'required', 'when' => function () {
                return false;
            }, 'whenClient' => "function(attribute, value) {
                      return !$('#treatment-per_item').is(':checked');
                  }"],
            ['value', 'string', 'max' => 255],
            ['uploaded_image', 'safe']
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
            'value' => Yii::t('app', 'Value'),
            'status' => Yii::t('app', 'Status (Active/Not active)'),
            'reg_description' => Yii::t('app', 'Description while doctor registration'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryTreatments()
    {
        return $this->hasMany(InquiryTreatment::className(), ['treatment_param_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTreatment()
    {
        return $this->hasOne(Treatment::className(), ['id' => 'treatment_id']);
    }

    public function getFilledSeverity()
    {
        return $this->hasMany(TreatmentParamSeverity::className(), ['param_id' => 'id'])->where(['is not', 'severity_id', null]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeverity()
    {
        return $this->hasMany(TreatmentParamSeverity::className(), ['param_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrandProvided()
    {
        return $this->hasMany(BrandProvidedTreatment::className(), ['treatment_param_id' => 'id']);
    }

    public function upload()
    {
        $name = \Yii::$app->security->generateRandomString(32);
        $user_id = \Yii::$app->user->id;
        $this->icon_path = $user_id . '/' . $name . '.' . $this->uploaded_image->extension;
        $this->icon_base_url = Yii::getAlias('@storageUrl/source');
        if (!is_dir(Yii::getAlias('@storage/web/source/' . $user_id))) {
            mkdir(Yii::getAlias('@storage/web/source/' . $user_id));
        }

        $data = new FileStorageItem();
        $data->component = 'fileStorage';
        $data->base_url = $this->icon_base_url;
        $data->path = $this->icon_path;
        $data->type = $this->icon_path;
        $data->size = $this->uploaded_image->size;
        $data->name = $name;

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $data->upload_ip = $ip;
        $data->created_at = time();
        if ($this->uploaded_image->saveAs(Yii::getAlias('@storage/web/source/' . $this->icon_path))) {
            $data->save(false);
            return true;
        };
        return $this->getErrors();
    }

    public function updateImage()
    {
        @unlink(Yii::getAlias('@source') . '/' . $this->icon_path);
        $this->upload();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvided()
    {
        return $this->hasOne(BrandProvidedTreatment::className(),['treatment_param_id'=>'id']);
    }
}
