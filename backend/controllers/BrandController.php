<?php

namespace backend\controllers;

use common\components\MultipleModel;
use common\components\StatusHelper;
use common\models\BrandParam;
use Yii;
use common\models\Brand;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * BrandController implements the CRUD actions for Brand model.
 */
class BrandController extends Controller
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
     * Lists all Brand models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Brand::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Brand model.
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
     * Creates a new Brand model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Brand();
        $param_models = [new BrandParam()];

        if ($model->load(Yii::$app->request->post())) {
            $model->icon_base_url = Yii::getAlias('@storageUrl') . '/source/';
            $model->icon_path = str_replace(Yii::getAlias('@storageUrl') . '/source/', '', $model->uploaded_image);

            $param_models = MultipleModel::createMultiple(BrandParam::classname());
            MultipleModel::loadMultiple($param_models, Yii::$app->request->post());
            foreach ($param_models as $param_index => $param_model) {
                $model_param = new BrandParam();
                $param_model->uploaded_image = UploadedFile::getInstance($model_param, "[{$param_index}]uploaded_image");
            }


            $valid = $model->validate();
            $valid = MultipleModel::validateMultiple($param_models) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        foreach ($param_models as $param_model) {
                            $param_model->brand_id = $model->id;
                            if (!empty($param_model->uploaded_image)) {
                                $param_model->upload();
                            }
                            if (!($flag = $param_model->save(false))) {
                                $transaction->rollBack();
                                break;
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
                'param_models' => (empty($param_models)) ? [new BrandParam()] : $param_models
            ]);
        }
    }

    /**
     * Updates an existing Brand model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $param_models = $model->brandParams;
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
            $oldIDs = ArrayHelper::map($param_models, 'id', 'id');
            $param_models = MultipleModel::createMultiple(BrandParam::classname(), $param_models);
            MultipleModel::loadMultiple($param_models, Yii::$app->request->post());

            foreach ($param_models as $param_index => $param_model) {
                $model_param = new BrandParam();
                $param_model->uploaded_image = UploadedFile::getInstance($model_param, "[{$param_index}]uploaded_image");
            }

            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($param_models, 'id', 'id')));

            $valid = $model->validate();
            $valid = MultipleModel::validateMultiple($param_models) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (!empty($deletedIDs)) {
                            BrandParam::deleteAll(['id' => $deletedIDs]);
                        }

                        foreach ($param_models as $param_model) {
                            $param_model->brand_id = $model->id;
                            if (!empty($param_model->uploaded_image)) {
                                $param_model->updateImage();
                            }
                            if (!($flag = $param_model->save(false))) {
                                $transaction->rollBack();
                                break;
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
                'param_models' => (empty($param_models)) ? [new BrandParam()] : $param_models
            ]);
        }
    }

    /**
     * Deletes an existing Brand model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $deleted_model = $model;
        if ($model->delete()) {
            BrandParam::deleteAll(['brand_id' => $deleted_model->id]);
            @unlink(Yii::getAlias('@source') . '/' . $deleted_model->icon_path);
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Brand model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Brand the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Brand::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
