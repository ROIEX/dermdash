<?php

namespace backend\controllers;

use common\components\StatusHelper;
use common\models\Brand;
use common\models\DoctorBrand;
use common\models\DoctorSignup;
use common\models\Inquiry;
use common\models\DoctorTreatment;
use common\models\Payment;
use common\models\Treatment;
use common\models\User;
use common\models\UserDocuments;
use common\models\UserProfile;
use kartik\mpdf\Pdf;
use Yii;
use common\models\Doctor;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\base\MultiModel;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * DoctorController implements the CRUD actions for Doctor model.
 */
class DoctorController extends Controller
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
     * Lists all Doctor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Doctor::find()->not_deleted()
                ->with('profile')
                ->with('user')
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Doctor model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $payment_model = new ActiveDataProvider([
            'query' => Payment::find()->where(['doctor_id' => $model->user_id]),
        ]);

        return $this->render('view', [
            'model' => $model,
            'payment_model' => $payment_model,
        ]);
    }

    /**
     * Creates a new Doctor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->layout = 'base';
        $model = new DoctorSignup();
        $brands = Brand::find()->all();
        $treatments = Treatment::find()->all();
        if (Yii::$app->request->isAjax) {
            $model->load(Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('alert', [
                'options' => ['class' => 'alert-success'],
                'body' => Yii::t('app', 'Account has been successfully created')
            ]);
            return $this->redirect(['doctor/update', 'id' => Yii::$app->user->identity->doctor->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'brands' => $brands,
                'treatments' => $treatments,
            ]);
        }
    }

    /**
     * Updates an existing Doctor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->can('administrator')) {
            $doctor_id = (int)$id;
        } else {
            $doctor_id = Yii::$app->user->identity->doctor->id;
        }

        $doctor_model = $this->findModel($doctor_id);
        $treatment = new DoctorTreatment();
        $brand = new DoctorBrand();
        $user_model = User::findOne(['id' => $doctor_model->user_id]);
        $user_profile = UserProfile::findOne(['user_id' => $doctor_model->user_id]);
        $brands = Brand::find()->all();
        $treatments = Treatment::find()->all();
        $selected_treatments = DoctorTreatment::getSelectedIdList($doctor_model->user_id);
        $selected_brands = DoctorBrand::getSelectedIdList($doctor_model->user_id);
        $selected_brands_dropdown_prices = $brand->getDropdownPrices($doctor_model->user_id);
        $doctor_model->treatment_discounts = $treatment->getSelectedDiscountsArray($doctor_model->user_id);
        $model = new MultiModel([
            'models' => [
                'doctor_model' => $doctor_model,
                'user_model' =>  $user_model,
                'user_profile' => $user_profile,
            ]
        ]);

        if (Yii::$app->request->isAjax) {
            $model->load(Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model->getModel('doctor_model'));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                $brand->updateSelected($model->getModel('doctor_model'));
                $treatment->updateSelected($model->getModel('doctor_model'));

            };

            Yii::$app->session->setFlash('alert', [
                'options'=>['class'=>'alert-success'],
                'body'=>Yii::t('app', 'Account has been successfully updated')
            ]);
            if (Yii::$app->user->can('administrator')) {
                return $this->redirect(['view', 'id' => $model->getModel('doctor_model')->id]);
            } else {
                return $this->redirect(['doctor/update', 'id' => Yii::$app->user->identity->doctor->id]);
            }

        } else {
            return $this->render('update', [
                'model' => $model,
                'brands' => $brands,
                'treatments' => $treatments,
                'selected_treatments' => $selected_treatments,
                'selected_brands' => $selected_brands,
                'selected_brands_dropdown_prices' => $selected_brands_dropdown_prices,
            ]);
        }
    }

    /**
     * Deletes an existing Doctor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $doctor_model = $this->findModel($id);
        $doctor_model->status = StatusHelper::STATUS_NOT_ACTIVE;
        $user_model = User::findOne(['id' => $doctor_model->user_id]);
        $user_model->status = User::STATUS_DELETED;
        DoctorBrand::deleteAll(['user_id' => $doctor_model->user_id]);
        DoctorTreatment::deleteAll(['user_id' => $doctor_model->user_id]);
        $doctor_model->update(false);
        $user_model->update(false);
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * Toggle doctor status active/unactive
     */
    public function actionChangeStatus($id)
    {
        if(Yii::$app->getrequest()->isAjax) {
            $doctor = Doctor::find()->where(['user_id' => (int)$id])->one();
            $doctor->status = !$doctor->status;
            $doctor->update(false);
        }
    }

    public function actionPriceDocument($user_id)
    {
        $selected_brands = DoctorBrand::getPricedBrands($user_id);
        $selected_treatments = DoctorTreatment::getPricedTreatments($user_id);
        return $this->generateDocument($selected_brands, $selected_treatments);
    }

    private function generateDocument($selected_brands, $selected_treatments)
    {
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'filename'=> Yii::$app->security->generateRandomString(16) . '.pdf',
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_DOWNLOAD,
            'content' => $this->renderPartial('price-generation/view', ['brands' => $selected_brands, 'treatments' => $selected_treatments])
        ]);

        return $pdf->render();
    }

    /**
     * Finds the Doctor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Doctor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Doctor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function AgreementSign($doctor)
    {
        if (!is_dir(Yii::getAlias('@storage/web/source/pdf'))) {
            mkdir(Yii::getAlias('@storage/web/source/pdf'));
        }

        $pdf_list = [
            [
                'content' => $this->renderPartial('pdf/Business_Associate_Agreement', ['doctor' => $doctor]),
                'filename' => Yii::$app->security->generateRandomString(16) . '.pdf',
                'css' => Yii::getAlias('@backend/web/css/Business_Associate_Agreement.css'),
                'type' => UserDocuments::BUSINESS_ASSOCIATE_AGREEMENT

            ],
            [
                'content' => $this->renderPartial('pdf/Independent_Contractor_Agreement', ['doctor' => $doctor]),
                'filename' => Yii::$app->security->generateRandomString(16) . '.pdf',
                'css' => Yii::getAlias('@backend/web/css/Independent_Contractor_Agreement.css'),
                'type' => UserDocuments::INDEPENDENT_CONTRACTOR_AGREEMENT
            ]
        ];

        foreach($pdf_list as $pdf) {
            $create_pdf = new Pdf([
                'mode' => Pdf::MODE_CORE,
                'filename'=> Yii::getAlias('@storage/web/source/pdf/') . $pdf['filename'],
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_FILE,
                'content' => $pdf['content'],
                'cssFile' => $pdf['css'],
            ]);

            $create_pdf->render();
            $document = new UserDocuments();
            $document->savePdf($pdf, Yii::$app->user->identity->id);
        }
    }
}
