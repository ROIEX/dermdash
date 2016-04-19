<?php
/**
 * Created by PhpStorm.
 * User: kharalampidi
 * Date: 18.01.16
 * Time: 19:34
 */

namespace frontend\modules\api\v1\models;


use Yii;
use yii\base\Model;

class PromoCode extends \common\models\PromoCode
{
    public $email;

    /**
     * @return array
     */
    public function rules()
    {
        return array_merge([
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'You can`t invite this person'],
        ],parent::rules());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge([
            'email' => Yii::t('app', 'Email'),
        ],parent::attributeLabels());
    }
}