<?php
/**
 * Created by PhpStorm.
 * User: rexit
 * Date: 29.12.15
 * Time: 11:08
 */

namespace frontend\modules\api\v1\controllers;

use common\models\BodyPart;
use common\models\Item;
use common\models\State;
use common\models\TreatmentCategory;
use frontend\modules\api\v1\resources\CollectData;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\rest\Controller;

class GetDataController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();


        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'states' => ['get'],
                'body-parts'=>['get'],
                'treatments'=>['get'],
            ]
        ];

        return $behaviors;
    }

    /**
     * Return all states (active and non-active)
     * If isset param active - return only active states.
     * @param bool $active
     * @return array
     */
    public function actionStates($active = false)
    {
        $activeQuery = $active ? State::find()->where(['status'=>State::STATUS_ACTIVE]) : State::find();
        $data = new ActiveDataProvider([
            'query'=> $activeQuery
        ]);
        return $data;
    }

    /**
     * Return body parts.
     * @return ActiveDataProvider
     */
    public function actionBodyParts()
    {
        return new ActiveDataProvider([
            'query' => BodyPart::find()
        ]);
    }

    /**
     * @return mixed|null
     */
    public function actionBrandsData()
    {
        $data = new CollectData(CollectData::BRANDS);
        return $data->getData();
    }

    /**
     * @return mixed|null
     */
    public function actionTreatmentData()
    {
        $data = new CollectData(CollectData::TREATMENTS);
        return $data->getData();
    }

}