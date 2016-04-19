<?php

namespace common\models;

use Yii;
use trntv\filekit\behaviors\UploadBehavior;

/**
 * This is the model class for table "treatment_param_severity".
 *
 * @property integer $id
 * @property integer $param_id
 * @property integer $severity_id
 * @property integer $brand_param_id
 * @property integer $count
 * @property string $icon_path
 * @property string $icon_url
 *
 * @property Severity $severity
 * @property BrandParam $brandParam
 */
class TreatmentParamSeverity extends \yii\db\ActiveRecord
{
    public $uploaded_image;

    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'treatment_param_severity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['param_id', 'severity_id', 'brand_param_id'], 'integer'],
            ['count', 'double'],
            [['icon_path', 'icon_url'], 'string'],
            ['uploaded_image', 'file'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'param_id' => Yii::t('app', 'Param'),
            'severity_id' => Yii::t('app', 'Severity'),
            'brand_param_id' => Yii::t('app', 'Brand'),
            'count' => Yii::t('app', 'Count'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeverity()
    {
        return $this->hasOne(Severity::className(), ['id' => 'severity_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrandParam()
    {
        return $this->hasOne(BrandParam::className(), ['id' => 'brand_param_id']);
    }

    public function upload()
    {
        if ($this->validate()) {

            $name = \Yii::$app->security->generateRandomString(32);
            $user_id = \Yii::$app->user->id;
            $this->icon_path = $user_id . '/' . $name . '.' . $this->uploaded_image->extension;
            $this->icon_url = Yii::getAlias('@storageUrl/source');
            if (!is_dir(Yii::getAlias('@storage/web/source/' . $user_id))) {
                mkdir(Yii::getAlias('@storage/web/source/' . $user_id));
            }

            $data = new FileStorageItem();
            $data->component = 'fileStorage';
            $data->base_url = $this
                ->icon_url;
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
        } else {
            return false;
        }
    }

    public function updateImage()
    {
        if ($this->validate()) {
            @unlink(Yii::getAlias('@source') . '/' . $this->icon_path);
            $this->upload();
        }
    }

    public static function deleteImages($id_list)
    {

        $severe_list = self::find()->where(['in', 'id', $id_list])->all();
        foreach ($severe_list as $severe_item) {
            @unlink(Yii::getAlias('@source') . '/' . $severe_item->icon_path);
        }
    }
}
