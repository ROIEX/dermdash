<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\components\StatusHelper;
use trntv\filekit\behaviors\UploadBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "{{%brand}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $icon_path
 * @property string $icon_base_url
 * @property string $sub_string
 * @property string $instruction
 * @property integer $treatment_id
 * @property integer $created_at
 * @property integer $status
 * @property integer $need_count
 * @property integer $type
 * @property integer $per
 * @property integer $is_dropdown
 * @property integer $param_multiselect
 * @property integer $reg_description
 *
 * @property Treatment $treatment
 * @property BrandParam[] $brandParams
 * @property BrandParam[] $activeBrandParams
 */
class Brand extends \yii\db\ActiveRecord
{
    public $uploaded_image;

    const TYPE_NEUROTOXIN = 1;
    const TYPE_FILLER = 2;

    const PER_UNIT = 0;
    const PER_SESSION = 1;
    const PER_VIAL = 2;
    const PER_1_SYRINGE = 3;
    const PER_1_5_SYRINGE = 4;

    const PART_HAND = 1;
    const PART_INNER = 2;
    const PART_OUTER = 3;
    const PART_FLANK = 4;
    const PART_DOUBLECHIN = 5;
    const PART_DECOLLETAGE = 6;
    const PART_CLEANFACE = 7;
    const PART_BROWS = 8;
    const PART_ABDOMEN = 9;
    const PART_JAWLINE = 10;
    const PART_NECK = 11;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%brand}}';
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
            [['sub_string', 'instruction', 'reg_description'], 'string'],
            [['treatment_id', 'created_at', 'status', 'type', 'per', 'is_dropdown', 'param_multiselect'], 'integer'],
            [['name', 'instruction', 'sub_string', 'per'], 'required'],
            [['name', 'icon_path', 'icon_base_url'], 'string', 'max' => 255],
            ['type', 'in', 'range' => [self::TYPE_FILLER, self::TYPE_NEUROTOXIN]],
            ['status', 'in', 'range' => [StatusHelper::STATUS_ACTIVE, StatusHelper::STATUS_NOT_ACTIVE]],
            ['status', 'default', 'value' => StatusHelper::STATUS_ACTIVE],
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
            'icon_path' => Yii::t('app', 'Icon Path'),
            'icon_base_url' => Yii::t('app', 'Icon Base Url'),
            'sub_string' => Yii::t('app', 'Sub String'),
            'instruction' => Yii::t('app', 'Instruction'),
            'treatment_id' => Yii::t('app', 'Treatment'),
            'created_at' => Yii::t('app', 'Created At'),
            'status' => Yii::t('app', 'Status (Active/Unactive)'),
            'type' => Yii::t('app', 'Type'),
            'per' => Yii::t('app', 'Per type'),
            'is_dropdown' => Yii::t('app', 'Check if dropdown'),
            'reg_description' => Yii::t('app', 'Description while doctor registration'),
        ];
    }

    public function beforeDelete()
    {
        foreach ($this->brandParams as $param) {
            if($param->inquiryBrands) {
                Yii::$app->session->setFlash('alert', [
                    'options' => ['class'=>'alert-danger'],
                    'body' => Yii::t('app', 'You can`t delete this brand because it has inquiries')
                ]);
                return false;
            }
        }
        return parent::beforeDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTreatment()
    {
        return $this->hasOne(Treatment::className(), ['id' => 'treatment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrandParams()
    {
        return $this->hasMany(BrandParam::className(), ['brand_id' => 'id']);
    }

    /**
     * @return $this
     */
    public function getActiveBrandParams()
    {
        return $this->hasMany(BrandParam::className(), ['brand_id' => 'id'])->where([BrandParam::tableName() . 'status' => StatusHelper::STATUS_ACTIVE]);
    }

    /**
     * @return array|null|ActiveRecord
     */
    public function getDefaultBrandParam()
    {
        return $this->hasOne(BrandParam::className(), ['brand_id' => 'id'])->orderBy('id')->one();
    }

    /**
     * @param $base_url
     * @param $path
     * @return bool|string
     */
    public static function getPhoto($base_url, $path)
    {
        return ($base_url && $path)
            ? Yii::getAlias($base_url . '/' . $path)
            : Yii::getAlias('@backendUrl'. '/img/default_brand.jpg');
    }

    public static function getTypeArray()
    {
        $array = [
             self::TYPE_NEUROTOXIN => Yii::t('app', 'Neurotoxin'),
             self::TYPE_FILLER => Yii::t('app', 'Filler')
        ];
        return $array;
    }

    public static function getType($type)
    {
        $array = self::getTypeArray();
        return $array[$type];
    }

    public static function getPerArray()
    {
        $array = [
            self::PER_UNIT => Yii::t('app', 'Unit'),
            self::PER_SESSION => Yii::t('app', 'Session'),
            self::PER_VIAL => Yii::t('app', 'Vial'),
            self::PER_1_SYRINGE => Yii::t('app', 'Syringe'),
            self::PER_1_5_SYRINGE => Yii::t('app', 'Syringe'),
        ];
        return $array;
    }

    public static function getBodyPartArray()
    {
        $array = [
            self::PART_HAND => Yii::t('app', 'Hand'),
            self::PART_INNER => Yii::t('app', 'Inner'),
            self::PART_OUTER => Yii::t('app', 'Outer'),
            self::PART_FLANK => Yii::t('app', 'Flank'),
            self::PART_DOUBLECHIN => Yii::t('app', 'Doublechin'),
            self::PART_DECOLLETAGE => Yii::t('app', 'Decolletage'),
            self::PART_CLEANFACE => Yii::t('app', 'Cleanface'),
            self::PART_BROWS => Yii::t('app', 'Brows'),
            self::PART_ABDOMEN => Yii::t('app', 'Abdomen'),
            self::PART_JAWLINE => Yii::t('app', 'Jawline'),
            self::PART_NECK => Yii::t('app', 'Neck'),
        ];
        return $array;
    }

    public static function getBodyPart($part)
    {
        $array = self::getBodyPartArray();
        return isset($array[$part]) ? $array[$part] : false;
    }

    public static function getPer($per)
    {
        $array = self::getPerArray();
        return $array[$per];
    }

    public static function getBrands()
    {
        $brand_param_list = [];
        $brand_array =  self::find()->with('brandParams')->all();

        if (!empty($brand_array)) {
            foreach ($brand_array as $brand) {
                $brand_param_list[$brand->name] = ArrayHelper::map($brand->brandParams, 'id', 'value');
            }
        }

        return $brand_param_list;
    }

    public static function getFillerBrandParams()
    {
        $filler_brand_params = [];
        $filler_brand_id_array = [15, 19, 20, 21, 22, 23, 24];
        $filler_brands = self::find()->where(['in', 'id', $filler_brand_id_array])->all();
        if (!empty($filler_brands)) {
            foreach ($filler_brands as $brand) {
                foreach ($brand->brandParams as $param) {
                    $filler_brand_params[] = $param->id;
                }

            }
            return array_flip($filler_brand_params);
        }
        return false;
    }
}
