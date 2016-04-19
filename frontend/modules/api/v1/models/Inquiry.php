<?php


namespace frontend\modules\api\v1\models;


use common\models\AdditionalAttributeItem;
use common\models\Brand;
use common\models\BrandParam;
use common\models\Inquiry as InquiryParent;
use common\models\InquiryBrand;
use common\models\InquiryTreatment;
use common\models\Severity;
use common\models\TreatmentParam;
use common\models\TreatmentParamSeverity;
use common\models\TreatmentSession;
use yii\base\InvalidParamException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\validators\ExistValidator;

class Inquiry extends Model
{
    const SCENARIO_TREATMENT = 'treatment';
    const SCENARIO_BRAND = 'brand';
    //common
    public $type;
    public $comment;

    //brands fields.
    public $brand_param_id;
    public $count;

    public $treatment_param_id;
    public $session_id;
   // public $additional_attribute_id;
    public $severity_id;
    public $treatment_intensity_id;

    public $id;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['type','required'],
            ['type','in','range'=>[InquiryParent::TYPE_TREATMENT, InquiryParent::TYPE_BRAND]],
            [['brand_param_id'],'required','on'=>self::SCENARIO_BRAND],
            ['count','integer','on'=>self::SCENARIO_BRAND],
            [['treatment_param_id'],'required','on'=>self::SCENARIO_TREATMENT],
            [['session_id','severity_id','treatment_intensity_id'],'safe','on'=>self::SCENARIO_TREATMENT],
            ['session_id','required','when'=>function($model){
                /* @var $model self */
                $treatmentParam = TreatmentParam::findOne($model->treatment_param_id);
                /* @var $treatmentParam TreatmentParam */
                return !empty($treatmentParam->treatment->treatmentSessions);
            },'on'=>self::SCENARIO_TREATMENT],
            ['comment','string'],
            ['brand_param_id', 'getExists', 'params'=>[
                'class'=>BrandParam::className()
            ]],
            ['treatment_param_id', 'getExists', 'params'=>[
                'class'=>TreatmentParam::className()
            ]],
            ['session_id', 'getExists', 'params'=>[
                'class'=>TreatmentSession::className()
            ]],
            ['severity_id', 'getExists', 'params'=>[
                'class'=>Severity::className()
            ]],
//            ['additional_attribute_id', 'getExists', 'params'=>[
//                'class'=>AdditionalAttributeItem::className()
//            ]],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function getExists($attribute, $params)
    {
        if (!$params['class']) {
            throw new InvalidParamException;
        }
        $className = $params['class'];
        if (!is_array($this->$attribute)) {
            /* @var $className ActiveRecord */
            if ($this->$attribute) {
                $model = $className::findOne($this->$attribute);
                if ($model == null) {
                    $this->addError($attribute,\Yii::t('app','Invalid {attribute}.',[
                        'attribute'=>$attribute
                    ]));
                }
            }
        } else {
            if ($this->$attribute) {
                foreach ($this->$attribute as $attribute_item) {
                    /* @var $className ActiveRecord */
                    $model = $className::findAll($attribute_item);
                    if (count($model) != count($attribute_item)) {
                        $this->addError($attribute,\Yii::t('app','Invalid {attribute}.',[
                            'attribute'=>$attribute
                        ]));
                    }
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function setNeededScenario()
    {
        switch($this->type){
            case InquiryParent::TYPE_BRAND:
                $this->setScenario(self::SCENARIO_BRAND);
                break;
            case InquiryParent::TYPE_TREATMENT:
                $this->setScenario(self::SCENARIO_TREATMENT);
        }
    }

    /**
     * @return string
     */
    public function getInquiryClassName()
    {
        $className = '';
        switch($this->type){
            case InquiryParent::TYPE_BRAND:
                $className = InquiryBrand::className();
                break;
            case InquiryParent::TYPE_TREATMENT:
                $className = InquiryTreatment::className();
        }
        return $className;
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_BRAND] = ['brand_param_id', 'count'];
        $scenarios[self::SCENARIO_TREATMENT] = ['treatment_param_id', 'session_id', 'severity_id', 'additional_attribute_id', 'treatment_intensity_id'];
        return $scenarios;
    }

    public function save()
    {
        $model = new InquiryParent();
        $model->type = $this->type;

        if (is_array($this->getAttributes()['brand_param_id'])) {
            if (count($this->getAttributes()['brand_param_id']) > 1) {
                $brand_param = BrandParam::findOne([$this->getAttributes()['brand_param_id'][0]]);
                if ($brand_param->brand->param_multiselect != 1) {
                    \Yii::$app->response->setStatusCode(409);
                    $this->addError('treatment_param_id', \Yii::t('app', 'This brand does not allow multiselect') );
                    return false;
                }
            }
        }


        if ($this->getAttributes()['type'] !=2) {
            if (!isset($this->getAttributes()['session_id'])) {
                \Yii::$app->response->setStatusCode(409);
                $this->addError('session_id', \Yii::t('app', 'You must add session value') );
                return false;
            }
        }


        if (is_array($this->getAttributes()['treatment_param_id'])) {
            if (count($this->getAttributes()['treatment_param_id']) > 1) {
                $treatment_param = TreatmentParam::findOne([$this->getAttributes()['treatment_param_id'][0]]);
                if ($treatment_param->treatment->param_multiselect != 1) {
                    \Yii::$app->response->setStatusCode(409);
                    $this->addError('treatment_param_id', \Yii::t('app', 'This treatment does not allow multiselect') );
                    return false;
                }

                foreach ($this->getAttributes()['treatment_param_id'] as $treatment_param_id) {
                    if (isset($this->getAttributes()['severity_id'])) {
                        if (!isset($this->getAttributes()['severity_id'][$treatment_param_id])) {
                            \Yii::$app->response->setStatusCode(409);
                            $this->addError('severity_id', \Yii::t('app', 'You must add severity value to each treatment param') );
                            return false;
                        }
                    }

                    if (isset($this->getAttributes()['treatment_intensity_id'])) {
                        if (!isset($this->getAttributes()['treatment_intensity_id'][$treatment_param_id])) {
                            \Yii::$app->response->setStatusCode(409);
                            $this->addError('treatment_intensity_id', \Yii::t('app', 'You must add intensity value to each treatment param') );
                            return false;
                        }
                    }
                }
            }
        }

        if ($model->save()) {
            $className = $this->getInquiryClassName();
            /* @var $inquiry InquiryTreatment */
            if ($className == InquiryTreatment::className()) {
                $inquiry = new $className;
                $inquiry->inquiry_id = $model->id;
                $inquiry->treatment_intensity_id = $this->treatment_intensity_id;
                $inquiry->setAttributes($this->getAttributes());
                //if treatment param is array we save many records.
                if (is_array($inquiry->treatment_param_id)) {
                    $valid = true;
                    foreach ($inquiry->treatment_param_id as $param) {
                        $manySave = new $className;
                        /* @var $manySave InquiryTreatment */
                        $manySave->setAttributes($this->getAttributes());
                        $manySave->inquiry_id = $model->id;
                        $manySave->treatment_param_id = $param;
                        $manySave->severity_id = isset($this->getAttributes()['severity_id'][$param]) ? $this->getAttributes()['severity_id'][$param] : null;
                        $manySave->treatment_intensity_id = isset($this->getAttributes()['treatment_intensity_id'][$param]) ? $this->getAttributes()['treatment_intensity_id'][$param] : null;
                        $valid = $valid && $manySave->save();
                        $this->id = $inquiry->inquiry_id;
                    }

                    //if body part param is array we save many records.
                } elseif (!is_array($inquiry->treatment_param_id)) {
                    $valid = true;
                        $manySave = new $className;
                        /* @var $manySave InquiryTreatment */
                        $manySave->setAttributes($this->getAttributes());
                        $manySave->inquiry_id = $model->id;
                        $manySave->severity_id = isset($this->getAttributes()['severity_id']) ? $this->getAttributes()['severity_id'] : null;
                        $manySave->treatment_intensity_id = isset($this->getAttributes()['treatment_intensity_id']) ? $this->getAttributes()['treatment_intensity_id'] : null;
                        $valid = $valid && $manySave->save();
                        $this->id = $inquiry->inquiry_id;
                    //if both many we save all records into db.
                } else {
                    $save = $inquiry->save();
                    $this->id = $inquiry->inquiry_id;
                    return $save;
                }
                //If many records not saved - delete $model.
                if (!$valid) {
                    $model->delete();
                }
                return $valid;
            } else {
                $inquiry = new $className;
                $inquiry->setAttributes($this->getAttributes());

                if (is_array($inquiry->brand_param_id)) {
                    $valid = true;
                    foreach ($inquiry->brand_param_id as $param) {
                        $manySave = new $className;
                        /* @var $manySave InquiryBrand */
                        $manySave->setAttributes($this->getAttributes());
                        $manySave->inquiry_id = $model->id;
                        $manySave->brand_param_id = $param;
                        $valid = $valid && $manySave->save();
                        $this->id = $manySave->inquiry_id;
                    }

                    if (!$valid) {
                        $model->delete();
                    }
                    return $valid;

                } else {
                    $inquiry->inquiry_id = $model->id;
                    if ($inquiry->save()) {
                        $this->id = $inquiry->inquiry_id;
                        return $this->id;

                    } else{
                        $model->delete();
                        return false;
                    }
                }
            }
        }
        return false;
    }
}