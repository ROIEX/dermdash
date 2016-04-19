<?php

namespace backend\controllers;

use common\models\AdditionalAttributeItem;
use common\models\Brand;
use common\models\BrandProvidedTreatment;
use common\models\TreatmentIntensity;
use common\models\TreatmentParam;
use common\models\TreatmentParamSeverity;
use common\models\TreatmentSession;
use Yii;
use common\models\Treatment;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\MultipleModel;
use common\components\StatusHelper;
use yii\base\Exception;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

/**
 * TreatmentController implements the CRUD actions for Treatment model.
 */
class TreatmentController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Treatment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Treatment::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Treatment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Treatment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate()
    {
        $model = new Treatment();
        $param_models = [new TreatmentParam()];
        $session_models = [new TreatmentSession()];
        $severity_models = [[new TreatmentParamSeverity()]];
        $intensity_models = [new TreatmentIntensity()];

        if ($model->load(Yii::$app->request->post())) {
            $model->icon_base_url = Yii::getAlias('@storageUrl') . '/source/';
            $model->icon_path = str_replace(Yii::getAlias('@storageUrl') . '/source/', '', $model->uploaded_image);

            $param_models = MultipleModel::createMultiple(TreatmentParam::classname());
            MultipleModel::loadMultiple($param_models, Yii::$app->request->post());

            foreach ($param_models as $param_index => $param_model) {
                $model_param = new TreatmentParam();
                $param_model->uploaded_image = UploadedFile::getInstance($model_param, "[{$param_index}]uploaded_image");
            }

            $intensity_models = MultipleModel::createMultiple(TreatmentIntensity::classname());
            MultipleModel::loadMultiple($intensity_models, Yii::$app->request->post());

            if (isset($_POST['TreatmentParamSeverity'][0][0])) {
                foreach ($_POST['TreatmentParamSeverity'] as $indexParam => $severe_items) {
                    foreach ($severe_items as $indexItem => $item) {
                        $data['TreatmentParamSeverity'] = $item;
                        $modelSevere = new TreatmentParamSeverity();
                        $modelSevere->load($data);
                        $modelSevere->uploaded_image = UploadedFile::getInstance($modelSevere, "[{$indexParam}][{$indexItem}]uploaded_image");

                        $severity_models[$indexParam][$indexItem] = $modelSevere;
                        $valid = $modelSevere->validate();
                    }
                }
            }

            $session_models = MultipleModel::createMultiple(TreatmentSession::classname());
            MultipleModel::loadMultiple($session_models, Yii::$app->request->post());
            $session_models = array_filter($session_models, function($item) {
                if (trim($item->session_count) != '') {
                    return $item;
                }
            });

            $valid = $model->validate() && isset($valid) ? $valid : true;
            $valid = MultipleModel::validateMultiple($param_models) && $valid;
            $valid = MultipleModel::validateMultiple($session_models) && $valid;
            $valid = MultipleModel::validateMultiple($intensity_models) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        //saving treatment params
                        if ($model->per_item == true) {

                            $param_models[0]->treatment_id = $model->id;
                            $param_models[0]->value = '1';
                            $param_models[0]->status = StatusHelper::STATUS_ACTIVE;
                            if (! ($flag = $param_models[0]->save(false))) {
                                $transaction->rollBack();
                            }
                        } else {
                            foreach ($param_models as $param_index => $param_model) {
                                if ($flag === false) {
                                    break;
                                }

                                $param_model->treatment_id = $model->id;
                                if (!empty($param_model->uploaded_image)) {
                                    $param_model->upload();
                                }
                                if (!($flag = $param_model->save(false))) {
                                    break;
                                }

                                if (isset($severity_models)) {
                                    if (isset($severity_models[$param_index]) && is_array($severity_models[$param_index])) {

                                        foreach ($severity_models[$param_index] as $severity_index => $severity_model) {

                                            $severity_model->param_id = $param_model->id;
                                            if (!empty($severity_model->uploaded_image)) {
                                                $severity_model->upload();
                                            }
                                            if (!($flag = $severity_model->save(false))) {
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        //saving intensity values
                        $intensity_models = array_filter($intensity_models, function($item) {
                            if (trim($item->brand_param_id) != '') {
                                return $item;
                            }
                        });
                        if (!empty($intensity_models)) {
                            foreach ($intensity_models as $intensity_model) {
                                if ($flag === false) {
                                    break;
                                }

                                $intensity_model->treatment_id = $model->id;

                                if (!($flag = $intensity_model->save(false))) {
                                    break;
                                }
                            }
                        }

                        //saving session values
                        if ($model->per_session == true) {
                            $session_model = new TreatmentSession();
                            $session_model->treatment_id = $model->id;
                            $session_model->session_count = '1';
                            if (! ($flag = $session_model->save(false))) {
                                $transaction->rollBack();
                            }
                        } else {
                            foreach ($session_models as $session_model) {
                                $session_model->treatment_id = $model->id;
                                if (! ($flag = $session_model->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'param_models' => (empty($param_models)) ? [new TreatmentParam()] : $param_models,
                'session_models' => (empty($session_models)) ? [new TreatmentSession()] : $session_models,
                'severity_models' => (empty($severity_models)) ? [[new TreatmentParamSeverity()]] : $severity_models,
                'intensity_models' => (empty($intensity_models)) ? [new TreatmentIntensity()] : $intensity_models,
            ]);
        }
    }

    public function actionCreateBrandProvided()
    {
        $model = new Treatment();
        $param_models = [new TreatmentParam()];
        $session_models = [new TreatmentSession()];
        $brand_provided_models = [[new BrandProvidedTreatment()]];
        $model->uploaded_image = $model->icon_base_url . $model->icon_path;
        $old_img = $model->uploaded_image;

        if ($model->load(Yii::$app->request->post())) {
            if ($old_img != $model->uploaded_image) {
                if ($model->uploaded_image == '') {
                    $model->icon_base_url = '';
                    $model->icon_path = '';
                } else {
                    $model->icon_base_url = Yii::getAlias('@storageUrl') . '/source/';
                    $model->icon_path = str_replace(Yii::getAlias('@storageUrl') . '/source/', '', $model->uploaded_image);
                }
            }

            $param_models = MultipleModel::createMultiple(TreatmentParam::classname());
            MultipleModel::loadMultiple($param_models, Yii::$app->request->post());

            foreach ($param_models as $param_index => $param_model) {
                $model_param = new TreatmentParam();
                $param_model->uploaded_image = UploadedFile::getInstance($model_param, "[{$param_index}]uploaded_image");
            }

            if (isset($_POST['BrandProvidedTreatment'][0][0])) {
                foreach ($_POST['BrandProvidedTreatment'] as $indexParam => $brand_items) {
                    foreach ($brand_items as $indexItem => $item) {
                        $data['BrandProvidedTreatment'] = $item;
                        $modelBrandProvided = new BrandProvidedTreatment();
                        $modelBrandProvided->load($data);

                        $brand_provided_models[$indexParam][$indexItem] = $modelBrandProvided;
                        $valid = $modelBrandProvided->validate();
                    }
                }
            }

            $session_models = MultipleModel::createMultiple(TreatmentSession::classname());
            MultipleModel::loadMultiple($session_models, Yii::$app->request->post());
            $session_models = array_filter($session_models, function($item) {
                if (trim($item->session_count) != '') {
                    return $item;
                }
            });

            $valid = $model->validate() && isset($valid) ? $valid : true;
            $valid = MultipleModel::validateMultiple($param_models) && $valid;
            $valid = MultipleModel::validateMultiple($session_models) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        //saving treatment params
                        if ($model->per_item == true) {

                            $param_models[0]->treatment_id = $model->id;
                            $param_models[0]->value = '1';
                            $param_models[0]->status = StatusHelper::STATUS_ACTIVE;
                            if (! ($flag = $param_models[0]->save(false))) {
                                $transaction->rollBack();
                            }
                        } else {
                            foreach ($param_models as $param_index => $param_model) {
                                if ($flag === false) {
                                    break;
                                }

                                $param_model->treatment_id = $model->id;
                                if (!empty($param_model->uploaded_image)) {
                                    $param_model->upload();
                                }
                                if (!($flag = $param_model->save(false))) {
                                    break;
                                }

                                if (isset($brand_provided_models)) {
                                    if (isset($brand_provided_models[$param_index]) && is_array($brand_provided_models[$param_index])) {
                                        foreach ($brand_provided_models[$param_index] as $index => $brand_provided_model) {
                                            $brand_provided_model->treatment_param_id = $param_model->id;
                                            if (!($flag = $brand_provided_model->save(false))) {
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        //saving session values
                        if ($model->per_session == true) {
                            $session_model = new TreatmentSession();
                            $session_model->treatment_id = $model->id;
                            $session_model->session_count = '1';
                            if (! ($flag = $session_model->save(false))) {
                                $transaction->rollBack();
                            }
                        } else {

                            foreach ($session_models as $session_model) {
                                $session_model->treatment_id = $model->id;
                                if (! ($flag = $session_model->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        } else {
            return $this->render('brand-provided-form', [
                'model' => $model,
                'param_models' => (empty($param_models)) ? [new TreatmentParam()] : $param_models,
                'session_models' => (empty($session_models)) ? [new TreatmentSession()] : $session_models,
                'brand_provided_models' => (empty($brand_provided_models)) ? [[new BrandProvidedTreatment()]] : $brand_provided_models,
            ]);
        }
    }

    public function actionUpdateBrandProvided($id)
    {
        $model = $this->findModel($id);
        $param_models = $model->treatmentParams;
        $session_models = $model->treatmentSessions;
        $model->uploaded_image = $model->icon_base_url . $model->icon_path;
        $old_img = $model->uploaded_image;
        $brand_provided_models = [];
        $old_brand_provided_models = [];
        $old_brand_providedIDs = [];
        if (!empty($param_models)) {
            foreach ($param_models as $indexParam => $modelParam) {
                $models = $modelParam->brandProvided;
                if (!empty($models)) {
                    $brand_provided_models[$indexParam] = $models;
                    foreach ($models as $brand_provided_model) {
                        $old_brand_providedIDs[] = $brand_provided_model->id;
                        $old_brand_provided_models[$brand_provided_model->id] = $brand_provided_model;
                    }
                    $brand_provided_models[$indexParam] = $models;
                }
            }
        }

        if (count($session_models) == 1) {
            $model->per_session = true;
            $old_flag = true;
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($old_img != $model->uploaded_image) {
                if ($model->uploaded_image == '') {
                    $model->icon_base_url = '';
                    $model->icon_path = '';
                } else {
                    $model->icon_base_url = Yii::getAlias('@storageUrl') . '/source/';
                    $model->icon_path = str_replace(Yii::getAlias('@storageUrl') . '/source/', '', $model->uploaded_image);
                }
            }
            //reset
            $brand_provided_models = [];

            $oldIDs = ArrayHelper::map($param_models, 'id', 'id');
            $old_sessionIDS = ArrayHelper::map($session_models, 'id', 'id');

            $param_models = MultipleModel::createMultiple(TreatmentParam::classname(), $param_models);
            MultipleModel::loadMultiple($param_models, Yii::$app->request->post());
            foreach ($param_models as $param_index => $param_model) {
                $model_param = new TreatmentParam();
                $param_model->uploaded_image = UploadedFile::getInstance($model_param, "[{$param_index}]uploaded_image");
            }

            $session_models = MultipleModel::createMultiple(TreatmentSession::classname(), $session_models);
            MultipleModel::loadMultiple($session_models, Yii::$app->request->post());
            $session_models = array_filter($session_models, function($item) {
                if (trim($item->session_count) != '') {
                    return $item;
                }
            });

            $valid = $model->validate();
            $valid = MultipleModel::validateMultiple($param_models) && $valid;
            $valid = MultipleModel::validateMultiple($session_models) && $valid;

            $brand_providedIDs = [];
            if (isset($_POST['BrandProvidedTreatment'][0][0])) {
                foreach ($_POST['BrandProvidedTreatment'] as $index_provided => $provided_models) {
                    $brand_providedIDs = ArrayHelper::merge($brand_providedIDs, array_filter(ArrayHelper::getColumn($provided_models, 'id')));
                    foreach ($provided_models as $index_brand => $item_brand) {
                        $data['BrandProvidedTreatment'] = $item_brand;
                        $model_brand_provided = (isset($item_brand['id']) && isset($old_brand_provided_models[$item_brand['id']])) ? $old_brand_provided_models[$item_brand['id']] : new BrandProvidedTreatment();
                        $model_brand_provided->load($data);

                        $brand_provided_models[$index_provided][$index_brand] = $model_brand_provided;
                        $valid = $model_brand_provided->validate();
                    }
                }
            }

            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($param_models, 'id', 'id')));
            $deleted_sessionIDs = array_diff($old_sessionIDS, array_filter(ArrayHelper::map($session_models, 'id', 'id')));
            $deleted_brand_providedIDs = array_diff($old_brand_providedIDs, $brand_providedIDs);

            if ($valid) {

                $transaction = \Yii::$app->db->beginTransaction();
                try {

                    if ($flag = $model->save(false)) {

                        if (! empty($deletedIDs)) {
                            TreatmentParam::deleteAll(['id' => $deletedIDs]);
                            TreatmentParamSeverity::deleteAll(['param_id' => $deletedIDs]);
                        }

                        if (! empty($deleted_sessionIDs)) {
                            TreatmentSession::deleteAll(['id' => $deleted_sessionIDs]);
                        }

                        if (! empty($deleted_brand_providedIDs)) {
                            BrandProvidedTreatment::deleteAll(['id' => $deleted_brand_providedIDs]);
                        }

                        //saving treatment params
                        foreach ($param_models as $param_index => $param_model) {
                            if ($flag === false) {
                                break;
                            }

                            $param_model->treatment_id = $model->id;
                            if (!empty($param_model->uploaded_image)) {
                                $param_model->updateImage();
                            }
                            if (!($flag = $param_model->save(false))) {
                                break;
                            }

                            if (isset($brand_provided_models[$param_index]) && is_array($brand_provided_models[$param_index])) {
                                foreach ($brand_provided_models[$param_index] as $provided_index => $provided_brand_model) {
                                    $provided_brand_model->treatment_param_id = $param_model->id;
                                    if (!($flag = $provided_brand_model->save(false))) {
                                        break;
                                    }
                                }
                            }
                        }

                        //saving session values
                        if ($model->per_session == true && $old_flag != true) {
                            TreatmentSession::deleteAll(['treatment_id' => $model->id]);
                            $session_model = new TreatmentSession();
                            $session_model->treatment_id = $model->id;
                            $session_model->session_count = '1';
                            if (! ($flag = $session_model->save(false))) {
                                $transaction->rollBack();
                            }
                        } else {
                            foreach ($session_models as $session_model) {
                                $session_model->treatment_id = $model->id;
                                if (! ($flag = $session_model->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }

        } else {
            return $this->render('brand-provided-form', [
                'model' => $model,
                'param_models' => (empty($param_models)) ? [new TreatmentParam()] : $param_models,
                'session_models' => (empty($session_models)) ? [new TreatmentSession()] : $session_models,
                'brand_provided_models' => (empty($brand_provided_models)) ? [[new BrandProvidedTreatment()]] : $brand_provided_models
            ]);
        }
    }

    /**
     * Updates an existing Treatment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $param_models = $model->treatmentParams;
        $session_models = $model->treatmentSessions;
        $intensity_models= $model->treatmentIntensity;
        $model->uploaded_image = $model->icon_base_url . $model->icon_path;
        $old_img = $model->uploaded_image;
        $severity_models = [];
        $old_severity_models = [];
        $old_severeIDs = [];
        $oldRooms = [];
        if (!empty($param_models)) {
            foreach ($param_models as $indexParam => $modelParam) {
                $models = $modelParam->severity;
                if (!empty($models)) {
                    $severity_models[$indexParam] = $models;
                    $oldRooms = ArrayHelper::merge(ArrayHelper::index($models, 'id'), $oldRooms);
                    foreach ($models as $severity_model) {
                        $old_severeIDs[] = $severity_model->id;
                        $old_severity_models[$severity_model->id] = $severity_model;
                    }
                    $severity_models[$indexParam] = $models;
                }
            }
        }

        if (count($session_models) == 1) {
            $model->per_session = true;
            $old_flag = true;
        }

        if (count($intensity_models) > 0) {
            $model->intensity = true;
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($old_img != $model->uploaded_image) {
                if ($model->uploaded_image == '') {
                    $model->icon_base_url = '';
                    $model->icon_path = '';
                } else {
                    $model->icon_base_url = Yii::getAlias('@storageUrl') . '/source/';
                    $model->icon_path = str_replace(Yii::getAlias('@storageUrl') . '/source/', '', $model->uploaded_image);
                }
            }

            //reset
            $severity_models = [];

            $oldIDs = ArrayHelper::map($param_models, 'id', 'id');
            $old_intensityIDs = ArrayHelper::map($intensity_models, 'id', 'id');
            $old_sessionIDS = ArrayHelper::map($session_models, 'id', 'id');

            $param_models = MultipleModel::createMultiple(TreatmentParam::classname(), $param_models);
            MultipleModel::loadMultiple($param_models, Yii::$app->request->post());
            foreach ($param_models as $param_index => $param_model) {
                $model_param = new TreatmentParam();
                $param_model->uploaded_image = UploadedFile::getInstance($model_param, "[{$param_index}]uploaded_image");
            }

            $session_models = MultipleModel::createMultiple(TreatmentSession::classname(), $session_models);
            MultipleModel::loadMultiple($session_models, Yii::$app->request->post());
            $session_models = array_filter($session_models, function($item) {
                if (trim($item->session_count) != '') {
                    return $item;
                }
            });

            $intensity_models = MultipleModel::createMultiple(TreatmentIntensity::classname(), $intensity_models);
            MultipleModel::loadMultiple($intensity_models, Yii::$app->request->post());

            $valid = $model->validate();
            $valid = MultipleModel::validateMultiple($param_models) && $valid;
            $valid = MultipleModel::validateMultiple($session_models) && $valid;
            $valid = MultipleModel::validateMultiple($intensity_models) && $valid;

            $severeIDs = [];
            if (isset($_POST['TreatmentParamSeverity'][0][0])) {
                foreach ($_POST['TreatmentParamSeverity'] as $indexParam => $severe_models) {
                    $severeIDs = ArrayHelper::merge($severeIDs, array_filter(ArrayHelper::getColumn($severe_models, 'id')));
                    foreach ($severe_models as $indexSevere => $severe_item) {
                        $data['TreatmentParamSeverity'] = $severe_item;
                        $modelSevere = (isset($severe_item['id']) && isset($old_severity_models[$severe_item['id']])) ? $old_severity_models[$severe_item['id']] : new TreatmentParamSeverity();
                        $modelSevere->load($data);
                        $modelSevere->uploaded_image = UploadedFile::getInstance($modelSevere, "[{$indexParam}][{$indexSevere}]uploaded_image");
                        $severity_models[$indexParam][$indexSevere] = $modelSevere;
                        $valid = $modelSevere->validate();
                    }
                }
            }

            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($param_models, 'id', 'id')));
            $deleted_intensityIDs = array_diff($old_intensityIDs, array_filter(ArrayHelper::map($intensity_models, 'id', 'id')));
            $deleted_sessionIDs = array_diff($old_sessionIDS, array_filter(ArrayHelper::map($session_models, 'id', 'id')));
            $deleted_severeIDs = array_diff($old_severeIDs, $severeIDs);

            if ($valid) {

                $transaction = \Yii::$app->db->beginTransaction();
                try {

                    if ($flag = $model->save(false)) {

                        if (! empty($deletedIDs)) {
                            TreatmentParam::deleteAll(['id' => $deletedIDs]);
                            TreatmentParamSeverity::deleteAll(['param_id' => $deletedIDs]);
                        }

                        if (! empty($deleted_intensityIDs)) {
                            TreatmentIntensity::deleteAll(['id' => $deleted_intensityIDs]);
                        }

                        if (! empty($deleted_sessionIDs)) {
                            TreatmentSession::deleteAll(['id' => $deleted_sessionIDs]);
                        }

                        if (! empty($deleted_severeIDs)) {
                            TreatmentParamSeverity::deleteImages($deleted_severeIDs);
                            TreatmentParamSeverity::deleteAll(['id' => $deleted_severeIDs]);
                        }
                        //saving treatment params
                        if ($model->per_item == true) {
                            $param_models[0]->treatment_id = $model->id;
                            $param_models[0]->value = '1';
                            $param_models[0]->status = StatusHelper::STATUS_ACTIVE;
                            if (! ($flag = $param_models[0]->save(false))) {
                                $transaction->rollBack();
                            }
                        } else {
                            foreach ($param_models as $param_index => $param_model) {
                                if ($flag === false) {
                                    break;
                                }

                                $param_model->treatment_id = $model->id;
                                if (!empty($param_model->uploaded_image)) {
                                    $param_model->updateImage();
                                }
                                if (!($flag = $param_model->save(false))) {
                                    break;
                                }

                                if (isset($severity_models[$param_index]) && is_array($severity_models[$param_index])) {

                                    foreach ($severity_models[$param_index] as $severity_index => $severity_model) {
                                        $severity_model->param_id = $param_model->id;
                                        if (!empty($severity_model->uploaded_image)) {
                                            $severity_model->updateImage();
                                        }
                                        if (!($flag = $severity_model->save(false))) {
                                            break;
                                        }
                                    }
                                }
                            }
                        }

                        //saving intensity values
                        $intensity_models = array_filter($intensity_models, function($item) {
                            if (trim($item->brand_param_id) != '') {
                                return $item;
                            }
                        });

                        if (!empty($intensity_models)) {
                            foreach ($intensity_models as $intensity_model) {
                                if ($flag === false) {
                                    break;
                                }

                                $intensity_model->treatment_id = $model->id;

                                if (!($flag = $intensity_model->save(false))) {
                                    break;
                                }
                            }
                        }


                        //saving session values
                        if ($model->per_session == true && $old_flag != true) {
                            TreatmentSession::deleteAll(['treatment_id' => $model->id]);
                            $session_model = new TreatmentSession();
                            $session_model->treatment_id = $model->id;
                            $session_model->session_count = '1';
                            if (! ($flag = $session_model->save(false))) {
                                $transaction->rollBack();
                            }
                        } else {
                            foreach ($session_models as $session_model) {
                                $session_model->treatment_id = $model->id;
                                if (! ($flag = $session_model->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }

        } else {
            return $this->render('update', [
                'model' => $model,
                'param_models' => (empty($param_models)) ? [new TreatmentParam()] : $param_models,
                'intensity_models' => (empty($intensity_models)) ? [new TreatmentIntensity()] : $intensity_models,
                'session_models' => (empty($session_models)) ? [new TreatmentSession()] : $session_models,
                'severity_models' => (empty($severity_models)) ? [[new TreatmentParamSeverity()]] : $severity_models
            ]);
        }
    }

    /**
     * Deletes an existing Treatment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $treatment_params = TreatmentParam::find()->where(['treatment_id' => $model->id])->all();
        $params_id_list = ArrayHelper::getColumn($treatment_params, 'id');
        $deleted_model = $model;
        if ($model->delete()) {
            $treatment_severe_params = TreatmentParamSeverity::find()->where(['in', 'param_id', $params_id_list])->all();
            $severe_id_list = ArrayHelper::getColumn($treatment_severe_params, 'id');

            BrandProvidedTreatment::deleteAll(['treatment_param_id' => $params_id_list]);
            TreatmentIntensity::deleteAll(['treatment_id' => $deleted_model->id]);

            TreatmentParamSeverity::deleteImages($severe_id_list);
            TreatmentParamSeverity::deleteAll(['id' => $severe_id_list]);

            TreatmentParam::deleteAll(['treatment_id' => $deleted_model->id]);
            AdditionalAttributeItem::deleteAll(['treatment_id' => $deleted_model->id]);
            @unlink(Yii::getAlias('@source') . '/' . $deleted_model->icon_path);
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Treatment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Treatment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Treatment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
