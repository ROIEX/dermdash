<?php

namespace common\models;

use Yii;
use common\components\StatusHelper;
use yii\db\Expression;

/**
 * This is the model class for table "{{%brand_param}}".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property string $value
 * @property integer $status
 * @property integer $reg_description
 * @property string $icon_base_url
 * @property string $icon_path
 * @property string $body_part
 *
 * @property Brand $brand
 * @property InquiryBrand[] $inquiryBrands
 * @property DoctorBrand[] $doctorBrands
 */
class BrandParam extends \yii\db\ActiveRecord
{
    public $uploaded_image;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%brand_param}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'status', 'body_part'], 'integer'],
            [['value', 'status'], 'required', 'when' => function() {
                return false;
            }, 'whenClient' => "function(attribute, value) {
                      return !$('#brand-need_count').is(':checked');
                  }"],
            [['value'], 'string', 'max' => 255],
            [['reg_description', 'icon_base_url', 'icon_path'], 'string', 'max' => 128],
            ['status', 'in', 'range' => [StatusHelper::STATUS_ACTIVE, StatusHelper::STATUS_NOT_ACTIVE]],
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
            'brand_id' => Yii::t('app', 'Brand ID'),
            'value' => Yii::t('app', 'Value'),
            'status' => Yii::t('app', 'Status(Active/Not active)'),
            'reg_description' => Yii::t('app', 'Description while doctor registration'),
            'body_part' => Yii::t('app', 'Body part'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDoctorBrands()
    {
        return $this->hasMany(DoctorBrand::className(),['brand_param_id'=>'id'])->orderBy(new Expression('rand()'))
            ->limit(Settings::getInquiryDoctorQuantity());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryBrands()
    {
        return $this->hasMany(InquiryBrand::className(), ['brand_param_id' => 'id']);
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
}
