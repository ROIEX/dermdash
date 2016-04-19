<?php
namespace frontend\controllers;

use cebe\markdown\Markdown;
use common\components\CryptHelper;
use common\components\StatusHelper;
use common\components\StripePayment;
use common\components\Yelp;
use common\models\Doctor;
use common\models\DoctorTreatment;
use common\models\Settings;
use Yii;
use frontend\models\ContactForm;
use yii\apidoc\helpers\ApiMarkdown;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null
            ],
            'set-locale'=>[
                'class'=>'common\actions\SetLocaleAction',
                'locales'=>array_keys(Yii::$app->params['availableLocales'])
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCurrent()
    {
        echo date('Y-m-d H:i:s');
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->contact(Yii::$app->params['adminEmail'])) {
                Yii::$app->getSession()->setFlash('alert', [
                    'body'=>Yii::t('frontend', 'Thank you for contacting us. We will respond to you as soon as possible.'),
                    'options'=>['class'=>'alert-success']
                ]);
                return $this->refresh();
            } else {
                Yii::$app->getSession()->setFlash('alert', [
                    'body'=>\Yii::t('frontend', 'There was an error sending email.'),
                    'options'=>['class'=>'alert-danger']
                ]);
            }
        }

        return $this->render('contact', [
            'model' => $model
        ]);
    }

    public function actionDoc()
    {
        \Yii::$app->response->format = 'html';
        $parser = new ApiMarkdown();
        $content = file_get_contents(\Yii::getAlias('@base/docs/api/methods.md'));
        $parser->html5 = true;
        $content = str_replace('{url}',Url::to(['/api/v1'],true),$content);
        $data = $parser->parse($content);
        return $this->render('doc',['data'=>$data]);
    }
}
