<?php

namespace backend\controllers;


use common\models\DoctorAnswer;
use common\models\Inquiry;
use common\models\Treatment;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DoctorAnswerController extends Controller
{

    public function actionIndex($inquiry_id)
    {
        $inquiry = Inquiry::findOne($inquiry_id);
        /* @var $inquiry Inquiry */
        $arrayDoctorList = ArrayHelper::map($inquiry->inquiryDoctorLists,'user_id','id');
        if ($inquiry == null || $inquiry->type == $inquiry::TYPE_BRAND || empty($arrayDoctorList[\Yii::$app->user->id])) {
            throw new NotFoundHttpException;
        }
        $treatment = $inquiry->inquiryTreatments[0]->treatmentParam->treatment;
       if (\Yii::$app->request->isPost) {
           $data = $_POST;

           if (!empty($data['_csrf'])) {
               unset($data['_csrf']);
           }

           $model = new DoctorAnswer();
           $model->answer = Json::encode($data);
           $model->inquiry_doctor_list_id = $arrayDoctorList[\Yii::$app->user->id];
           $model->save();
       }
        return $this->view($treatment);
    }

    /**
     * @param Treatment $treatment
     * @return string
     */
    private function view(Treatment $treatment)
    {
        $viewName = '';
        switch ($treatment->id) {
            case 1:
                $viewName = 'body-contouring';
                break;
            case 2:
                $viewName = 'chemical-peel';
                break;
            case 3:

                break;
            case 4:
                $viewName = 'fine-lines';
                break;
            case 5:

                break;
            case 6:

                break;
            case 7:

                break;
            case 8:

                break;
        }
        return $this->render($viewName,['treatment'=>$treatment]);
    }
}