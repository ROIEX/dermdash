<?php

namespace common\components;



use Stevenmaguire\Yelp\Client;
use Stevenmaguire\Yelp\Exception;
use yii\base\Component;

class Yelp extends Component
{
    private $consumerKey;
    private $consumerSecret;
    private $token;
    private $tokenSecret;
    private $apiHost;
    public $yelp;

    public function init()
    {
        $params = \Yii::$app->params['yelp'];
        $this->consumerKey = $params['consumerKey'];
        $this->consumerSecret = $params['consumerSecret'];
        $this->token = $params['token'];
        $this->tokenSecret = $params['tokenSecret'];
        $this->apiHost = $params['apiHost'];
        $this->yelp = new Client([
            'consumerKey' => $this->consumerKey,
            'consumerSecret' => $this->consumerSecret,
            'token' => $this->token,
            'tokenSecret' => $this->tokenSecret,
            'apiHost' => $this->apiHost
        ]);
    }

    /**
     * Search bussines data from Yelp with phone number.
     * @param $phone
     * @return array
     */
    public function searchByPhone($phone)
    {
        try{
            $searchByPhone = $this->yelp->searchByPhone(array('phone' => $phone, 'cc' => 'US'));
        }catch(Exception $e){
            return [
                'rating'=>null,
                'reviews'=>null
            ];
        }
        if (!empty($searchByPhone)) {
            $businesses = $searchByPhone->businesses;
        }
        $rating = null;
        $reviews = null;
        if (!empty($businesses)) {
            $rating = $businesses[0]->rating;
            $reviews = $businesses[0]->review_count;
        }
        return [
            'rating'=>$rating,
            'reviews'=>$reviews
        ];
    }


}