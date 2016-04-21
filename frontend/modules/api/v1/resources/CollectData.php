<?php

namespace frontend\modules\api\v1\resources;


use common\components\StatusHelper;
use common\models\AddAttributeItem;
use common\models\AdditionalAttribute;
use common\models\AdditionalAttributeItem;
use common\models\BodyPartTreatment;
use common\models\Brand;
use common\models\Item;
use common\models\PhotoParam;
use common\models\SubItem;
use common\models\Treatment;
use common\models\TreatmentParam;
use common\models\TreatmentParamSeverity;
use Yii;
use yii\base\InvalidParamException;

class CollectData
{
    public $category;
    const TEN_MINUTES_CACHE = 600;

    const BRANDS = 1;
    const TREATMENTS = 2;

    /**
     * CollectData constructor.
     * @param $category
     */
    public function __construct($category)
    {
        if (!in_array($category,$this->getCategories())) {
            throw new InvalidParamException(\Yii::t('app','Invalid category ID.'));
        }
        $this->category = $category;
    }

    /**
     * Collect data in category.
     */
    public function getData()
    {
        switch ($this->category) {
            case self::BRANDS:
                return $this->getBrandsData();
            break;
            case self::TREATMENTS:
                return $this->getTreatmentsData();
            break;
        }
        throw new InvalidParamException;
    }

    /**
     * @return array
     */
    private function getBrandsData()
    {
        $returnData = [];
        $allBrands = Brand::find()->where(['status' => StatusHelper::STATUS_ACTIVE])->all();
        foreach ($allBrands as $brand) {
            /* @var $brand Brand */
            $icon_url = $brand->icon_path ? $brand->icon_base_url . '/' . $brand->icon_path : false;
            $params = [];
            foreach ($brand->activeBrandParams as $param) {
                $params[] = [
                    'param_id'=>$param->id,
                    'body_part'=>Brand::getBodyPart($param->body_part),
                    'per_value'=>$param->value,
                    'icon' => $param->icon_path ? $param->icon_base_url . '/' . $param->icon_path : false
                ];
            }
            $returnData[] = [
                'brand_name'=>$brand->name,
                'sub_string'=>$brand->sub_string,
                'instruction'=>$brand->instruction,
                'icon_url'=>$icon_url,
                'treatment_id'=>$brand->treatment_id,
                'is_dropdown'=>$brand->is_dropdown,
                'param_multiselect'=>$brand->param_multiselect,
                'params'=>$params
            ];
        }
        return $returnData;
    }

    private function getTreatmentsData()
    {
        $returnData = [];
        $allTreatments = Treatment::find()->where(['status'=>StatusHelper::STATUS_ACTIVE])->all();

        foreach ($allTreatments as $treatment) {
            /* @var $treatment Treatment */
            $params = [];
            foreach ($treatment->treatmentParams as $param) {
                /* @var $param TreatmentParam */
                $severities = [];
                foreach ($param->filledSeverity as $severity) {
                    $icon_url = $severity->icon_path ? $severity->icon_url .'/'. $severity->icon_path : false;
                    /* @var $severity TreatmentParamSeverity */
                    $severities[$severity->severity_id] = [
                        'id'=>$severity->severity_id,
                        'name'=>$severity->severity->name,
                        'icon_url'=>$icon_url
                    ];
                }
                $params[] = [
                    'param_id'=>$param->id,
                    'per_value'=>$param->value,
                    'severity'=>$severities,
                    'icon_url'=>$param->icon_path ?  $param->icon_base_url .'/'. $param->icon_path : false
                ];
            }
            $sessions = [];
            foreach ($treatment->treatmentSessions as $session) {
                $sessions[] = [
                    'session_id'=>$session->id,
                    'count'=>$session->session_count
                ];
            }
            $icon_url = $treatment->icon_path ? Yii::$app->glide->createSignedUrl([
                'glide/index',
                'path' => $treatment->icon_path,
                'w' => 200
            ], true) : false;
            $intensities = [];
            foreach ($treatment->treatmentIntensity as $intensity) {
                $intensities[] = [
                    'id'=>$intensity->id,
                    'brand'=>$intensity->brandParam->brand->name,
                    'intensity_name'=>$intensity->intensity->name
                ];
            }
            $returnData[] = [
                'treatment_name'=>$treatment->name,
                'treatment_id' => $treatment->id,
                'sub_string'=>$treatment->sub_string,
                'instruction'=>$treatment->instruction,
                'icon_url'=>$icon_url,
                'param_multiselect'=>$treatment->param_multiselect,
                'select_both_button'=>$treatment->select_both_button,
                'buttons_in_row'=>$treatment->buttons_in_row,
                'session_buttons_position'=>$treatment->session_buttons_position,
                'params'=>$params,
                'sessions'=>$sessions,
                'additional_attributes'=>array_map(function($arr){
                    return [
                        'id'=>$arr['id'],
                        'value'=>$arr['value']
                    ];
                },$treatment->additionalAttributes),
                'intensities'=>$intensities
            ];
        }
        return $returnData;
    }

    /**
     * Return available categories in array.
     * @return array
     */
    private function getCategories()
    {
        return [
            self::BRANDS,
            self::TREATMENTS
        ];
    }

}