<?php

namespace common\behaviors;


use common\components\StatusHelper;
use common\models\Doctor;
use common\models\DoctorBrand;
use common\models\DoctorTreatment;
use common\models\Inquiry;
use common\models\InquiryBrand;
use common\models\InquiryDoctorList;
use common\models\InquiryTreatment;
use common\models\Settings;
use common\models\TreatmentIntensity;
use common\models\TreatmentIntensityDiscounts;
use common\models\TreatmentParamSeverity;
use common\models\User;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\InvalidParamException;
use yii\captcha\Captcha;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class SaveDoctorsBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave'
        ];
    }

    public function afterSave(Event $event)
    {
        $model = $this->owner;

        if ($model instanceof InquiryBrand) {
            $this->brand($model);
        } elseif ($model instanceof InquiryTreatment) {
            $this->treatment($model);
        }else {
            throw new InvalidParamException;
        }
    }

    /**
     * @param InquiryTreatment $model
     * @return string
     */
    private function treatment(InquiryTreatment $model)
    {
        if ($model->treatmentParam->provided) {
            $query = new Query();
            $query->select('`doctor`.`user_id`,`doctor_brand`.`price`')
                ->from(Doctor::tableName())
                ->innerJoin(DoctorBrand::tableName(),'`doctor`.`user_id` = `doctor_brand`.`user_id`')
                ->innerJoin(User::tableName(), 'user.id = doctor.user_id')
                ->where(['doctor_brand.brand_param_id' => $model->treatmentParam->provided->brandParam->id])
                ->andWhere(['doctor.status' => StatusHelper::STATUS_ACTIVE])
                ->andWhere(['!=', 'user.status', User::STATUS_DELETED])
                ->orderBy(new Expression('rand()'))
                ->limit(Settings::getInquiryDoctorQuantity());

            $command = $query->createCommand();
            $doctors = $command->queryAll();
            \Yii::$app->db->beginTransaction();

            foreach ($doctors as $doctor) {
                $modelList = new InquiryDoctorList();
                $modelList->setAttributes($doctor);
                $modelList->price = ($doctor['price'] * $model->treatmentParam->provided->count);
                $modelList->inquiry_id = $model->inquiry_id;
                $modelList->param_id = $model->treatment_param_id;
                $modelList->is_viewed_by_patient = InquiryDoctorList::VIEWED_STATUS_NO;
                $modelList->is_viewed = InquiryDoctorList::VIEWED_STATUS_NO;
                $modelList->save(false);
            }

            try {
                \Yii::$app->db->transaction->commit();
            } catch(Exception $e) {
                \Yii::$app->db->transaction->rollBack();
                $model->inquiry->delete();
                return $e->getMessage();
            }
            return true;
        }
        if (!$model->severity && !$model->treatmentIntensity) {

            $query = new Query();
            $query->select('`doctor`.`user_id`,`doctor_treatment`.`price`')
                ->from(Doctor::tableName())
                ->innerJoin(DoctorTreatment::tableName(),'`doctor`.`user_id` = `doctor_treatment`.`user_id`')
                ->innerJoin(User::tableName(), 'user.id = doctor.user_id')
                ->where(['doctor_treatment.treatment_param_id' => $model->treatment_param_id])
                ->andWhere(['doctor_treatment.treatment_session_id' => $model->session_id])
                ->andWhere(['doctor.status' => StatusHelper::STATUS_ACTIVE])
                ->andWhere(['!=', 'user.status', User::STATUS_DELETED])
                ->orderBy(new Expression('rand()'))
                ->limit(Settings::getInquiryDoctorQuantity());

            $command = $query->createCommand();
            $doctors = $command->queryAll();

            \Yii::$app->db->beginTransaction();

            foreach ($doctors as $doctor) {
                $modelList = new InquiryDoctorList();
                $modelList->setAttributes($doctor);
                $modelList->inquiry_id = $model->inquiry_id;
                $modelList->param_id = $model->treatment_param_id;
                $modelList->is_viewed = InquiryDoctorList::VIEWED_STATUS_NO;
                $modelList->is_viewed_by_patient = InquiryDoctorList::VIEWED_STATUS_NO;
                $modelList->save(false);
            }

            try {
                \Yii::$app->db->transaction->commit();
            } catch(Exception $e) {
                \Yii::$app->db->transaction->rollBack();
                $model->inquiry->delete();
                return $e->getMessage();
            }
        } elseif($model->treatmentIntensity && !$model->severity){


            $brand_query = DoctorBrand::find()
                ->where(['brand_param_id' =>  $model->treatmentIntensity->brand_param_id])
                ->all();

            $doctor_id_list = ArrayHelper::map($brand_query, 'user_id', 'user_id');


            $query = new Query();
            $query->select('`doctor`.`user_id`')
                ->from(Doctor::tableName())
                ->innerJoin(DoctorTreatment::tableName(),'`doctor`.`user_id` = `doctor_treatment`.`user_id`')
                ->innerJoin(User::tableName(), 'user.id = doctor.user_id')
                ->where(['doctor_treatment.treatment_param_id' => $model->treatment_param_id])
                ->andWhere(['doctor.status' => StatusHelper::STATUS_ACTIVE])
                ->andWhere(['in', 'doctor.user_id', $doctor_id_list])
                ->andWhere(['!=', 'user.status', User::STATUS_DELETED])
                ->orderBy(new Expression('rand()'))
                ->limit(Settings::getInquiryDoctorQuantity());

            $command = $query->createCommand();
            $doctors = $command->queryAll();
            $doctors = Doctor::find()->where(['in', 'user_id', $doctors])->all();

            \Yii::$app->db->beginTransaction();

            foreach ($doctors as $doctor) {

                $modelList = new InquiryDoctorList();
                $price = $doctor->getDoctorBrandPrice($model->treatmentIntensity->brand_param_id)->price * $model->treatmentIntensity->count;

                if ($model->session->session_count > 1) {
                    $discount = TreatmentIntensityDiscounts::find()
                        ->where(['treatment_id' => $model->treatmentParam->treatment->id])
                        ->andWhere(['session_id' => $model->session->id])
                        ->andWhere(['user_id' => $doctor->user_id])
                        ->one();
                $price = $price * $model->session->session_count * (1 - ($discount->discount_value / 100));
                }
                $modelList->user_id = $doctor->user_id;
                $modelList->price = $price;
                $modelList->inquiry_id = $model->inquiry_id;
                $modelList->param_id = $model->treatment_param_id;
                $modelList->is_viewed = InquiryDoctorList::VIEWED_STATUS_NO;
                $modelList->is_viewed_by_patient = InquiryDoctorList::VIEWED_STATUS_NO;
                $modelList->save(false);
            }

            try {
                \Yii::$app->db->transaction->commit();
            } catch(Exception $e) {
                \Yii::$app->db->transaction->rollBack();
                $model->inquiry->delete();
                return $e->getMessage();
            }
        } else {
            $brand_params_array = [];
            foreach ($model->getTreatmentSeveritiesByParam() as $severity) {
                $brand_params_array[] = $severity->brandParam->id;
            }

            $brand_query = DoctorBrand::find()
                ->where(['in', 'brand_param_id', $brand_params_array])
                ->all();
            $doctor_id_list = ArrayHelper::map($brand_query, 'user_id', 'user_id');

            $query = new Query();
            $query->select('`doctor`.`user_id`')
                ->from(Doctor::tableName())
                ->innerJoin(DoctorTreatment::tableName(),'`doctor`.`user_id` = `doctor_treatment`.`user_id`')
                ->innerJoin(User::tableName(), 'user.id = doctor.user_id')
                ->where(['doctor_treatment.treatment_param_id' => $model->treatment_param_id])
                ->andWhere(['doctor_treatment.treatment_session_id' => $model->session_id])
                ->andWhere(['doctor.status' => StatusHelper::STATUS_ACTIVE])
                ->andWhere(['!=', 'user.status', User::STATUS_DELETED])
                ->andWhere(['in', 'doctor.user_id', $doctor_id_list])
                ->orderBy(new Expression('rand()'))
                ->limit(Settings::getInquiryDoctorQuantity());


            $command = $query->createCommand();
            $doctors = $command->queryAll();
            $doctors = Doctor::find()->where(['in', 'user_id', $doctors])->all();

            \Yii::$app->db->beginTransaction();
            /** @var TreatmentParamSeverity $treatmentParamSeverity */
            $prices = [];

            foreach ($model->getTreatmentSeveritiesByParam() as $treatmentParamSeverity) {
                foreach ($doctors as $doctor) {
                    if (!empty($prices[$doctor->user_id])) {
                        $prices[$doctor->user_id] += $doctor->getDoctorBrandPrice($treatmentParamSeverity->brandParam->brand->brandParams[0]->id)->price * $treatmentParamSeverity->brandParam->value *$treatmentParamSeverity->count;
                    } else {
                        $prices[$doctor->user_id] = $doctor->getDoctorBrandPrice($treatmentParamSeverity->brandParam->brand->brandParams[0]->id)->price * $treatmentParamSeverity->brandParam->value *$treatmentParamSeverity->count;
                    }
                }
            }
            foreach ($prices as $doctor_id => $price) {
                $modelList = new InquiryDoctorList();
                $modelList->user_id = $doctor_id;
                $modelList->price = $price;
                $modelList->inquiry_id = $model->inquiry_id;
                $modelList->param_id = $model->treatment_param_id;
                $modelList->is_viewed = InquiryDoctorList::VIEWED_STATUS_NO;
                $modelList->is_viewed_by_patient = InquiryDoctorList::VIEWED_STATUS_NO;
                $modelList->save(false);
            }

            try {
                \Yii::$app->db->transaction->commit();
            } catch(Exception $e) {
                \Yii::$app->db->transaction->rollBack();
                $model->inquiry->delete();
                return $e->getMessage();
            }
        }
    }

    /**
     * @param InquiryBrand $model
     * @return string
     * @throws Exception
     */
    private function brand(InquiryBrand $model)
    {
        $query = new Query();

        if ($model->brandParam->brand->is_dropdown == 1) {
            $where = $model->brandParam->brand->brandParams[0]->id;
        } else {
           $where = $model->brand_param_id;
        }

        $query->select('`doctor`.`user_id`,`doctor_brand`.`price`')
            ->from(Doctor::tableName())
            ->innerJoin(DoctorBrand::tableName(),'`doctor`.`user_id` = `doctor_brand`.`user_id`')
            ->innerJoin(User::tableName(), 'user.id = doctor.user_id')
            ->where(['doctor_brand.brand_param_id' => $where])
            ->andWhere(['doctor.status' => StatusHelper::STATUS_ACTIVE])
            ->andWhere(['!=', 'user.status', User::STATUS_DELETED])
            ->orderBy(new Expression('rand()'))
            ->limit(Settings::getInquiryDoctorQuantity());

        $command = $query->createCommand();
        $doctors = $command->queryAll();
        \Yii::$app->db->beginTransaction();

        foreach ($doctors as $doctor) {
            $modelList = new InquiryDoctorList();
            $modelList->setAttributes($doctor);
            $modelList->inquiry_id = $model->inquiry_id;
            if ($model->brandParam->brand->is_dropdown == 1) {
                $modelList->price = $doctor['price'] * $model->brandParam->value;
            } else {
                $modelList->price = $doctor['price'];
            }

            $modelList->param_id = $model->brand_param_id;
            $modelList->is_viewed = InquiryDoctorList::VIEWED_STATUS_NO;
            $modelList->is_viewed_by_patient = InquiryDoctorList::VIEWED_STATUS_NO;
            $modelList->save(false);
        }

        try {
            \Yii::$app->db->transaction->commit();
        } catch(Exception $e) {
            \Yii::$app->db->transaction->rollBack();
            $model->inquiry->delete();
            return $e->getMessage();
        }
    }
}