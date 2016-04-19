<?php

namespace backend\controllers;

use common\models\TreatmentType;
use common\models\TreatmentTypeCount;
use yii\web\Controller;
use Yii;
use common\models\TreatmentCategory;
use yii\data\ActiveDataProvider;

class TreatmentSettingsController extends Controller
{
    public function actionIndex()
    {
        $category_list = new ActiveDataProvider([
            'query' => TreatmentCategory::find(),
        ]);

        $type_list = new ActiveDataProvider([
            'query' => TreatmentType::find(),
        ]);

        $type_count_list = new ActiveDataProvider([
            'query' => TreatmentTypeCount::find(),
        ]);

        return $this->render('index', [
            'category_list' => $category_list,
            'type_list' => $type_list,
            'type_count_list' => $type_count_list,
        ]);
    }

    public function actionCreateType()
    {
        $model = new TreatmentType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('type-create', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreateTypeCount()
    {
        $model = new TreatmentTypeCount();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('count-create', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreateCategory()
    {
        $model = new TreatmentCategory();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('alert', [
                'options'=>['class'=>'alert-success'],
                'body'=>Yii::t('app', 'Category has been created')
            ]);
            return $this->redirect(['index']);
        } else {
            return $this->render('category-create', [
                'model' => $model,
            ]);
        }
    }

    public function actionTypeDelete($id)
    {
        $type= TreatmentType::findOne(['id' => (int)$id]);
        $type->delete();
        return $this->redirect(['index']);
    }

    public function actionCategoryDelete($id)
    {
        $category = TreatmentCategory::findOne(['id' => (int)$id]);
        $category->delete();
        return $this->redirect(['index']);
    }

    public function actionCountDelete($id)
    {
        $category = TreatmentTypeCount::findOne(['id' => (int)$id]);
        $category->delete();
        return $this->redirect(['index']);
    }
}