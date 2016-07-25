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
        try {
            $searchByPhone = $this->yelp->searchByPhone(array('phone' => $phone, 'cc' => 'US'));
        } catch(Exception $e) {
            return [
                'rating' => null,
                'reviews' => null,
                'mobile_url' => null
            ];
        }
        if (!empty($searchByPhone)) {
            $businesses = $searchByPhone->businesses;
        }

        if (!empty($businesses)) {
            $rating =  isset($businesses[0]->rating) ? $businesses[0]->rating : null;
            $reviews =  isset($businesses[0]->review_count) ? $businesses[0]->review_count : null;
            $mobile_url = isset($businesses[0]->mobile_url) ? $businesses[0]->mobile_url : null;
            return [
                'rating' => $rating,
                'reviews' => $reviews,
                'mobile_url' => $mobile_url
            ];
        } else {
            return [
                'rating' => null,
                'reviews' => null,
                'mobile_url' => null
            ];
        }
    }


}