<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "doctor_photo".
 *
 * @property integer $id
 * @property integer $doctor_id
 * @property string $base_url
 * @property string $path
 * @property string $description
 */
class DoctorPhoto extends \yii\db\ActiveRecord
{
    public $uploaded_images;
    /**
     * @inheritdoc
     */
    
    public static function tableName()
    {
        return 'doctor_photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doctor_id'], 'integer'],
            [['description'], 'string'],
            [['base_url'], 'string', 'max' => 255],
            [['path'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'doctor_id' => Yii::t('app', 'User'),
            'base_url' => Yii::t('app', 'Base Url'),
            'path' => Yii::t('app', 'Path'),
            'description' => Yii::t('app', 'Description'),
        ];
    }
}
