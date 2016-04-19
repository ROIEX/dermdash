<?php

namespace common\models;


use DrewM\MailChimp\MailChimp;
use yii\base\Model;
use yii\helpers\Json;

class MailchimpList extends Model
{
    public $id = null;
    public $name; // required
    public $company; // required
    public $address1; // required
    public $address2;
    public $city; // requiredwiki
    public $state; // required
    public $zip; // required
    public $country; // required
    public $phone;
    public $from_name; // required
    public $from_email; // required
    public $subject; // required
    public $language; // required

    public $notify_on_subscribe;
    public $notify_on_unsubscribe;
    public $permission_reminder;
    public $email_type_option = false;
    public $use_archive_bar = true;
    public $visibility;

    const VISIBILITY_PUBLIC = 'pub';
    const VISIBILITY_PRIVATE = 'prv';

    /* @var $mailchimp MailChimp */
    private $mailchimp;

    public function init()
    {
        parent::init();
        $this->mailchimp =  new Mailchimp(\Yii::$app->params['mailchimpApiKey']);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->mailchimp->verify_ssl = false;
        }
    }


    public function rules()
    {
        return [
            [['name','company','address1','city','state','zip','country','from_name','from_email','subject','language', 'permission_reminder'],'required'],
            [['address2','phone','notify_on_subscribe','notify_on_unsubscribe','permission_reminder','email_type_option','use_archive_bar','visibility'],'safe'],
            [['from_email','notify_on_subscribe','notify_on_unsubscribe'],'email'],
            ['visibility','in','range'=>array_keys($this->getVisibility())]
        ];
    }

    /**
     * @return string
     */
    public function createJson()
    {
        $array = [
            'name'=>$this->name,
            'contact'=>[
                'company'=>$this->company,
                'address1'=>$this->address1,
                'address2'=>$this->address2,
                'city'=>$this->city,
                'state'=>$this->state,
                'zip'=>$this->zip,
                'country'=>$this->country,
                'phone'=>$this->phone
            ],
            'campaign_defaults'=>[
                'from_name'=>$this->from_name,
                'from_email'=>$this->from_email,
                'subject'=>$this->subject,
                'language'=>$this->language
            ],
            'notify_on_subscribe'=>$this->notify_on_subscribe,
            'notify_on_unsubscribe'=>$this->notify_on_unsubscribe,
            'email_type_option'=>$this->email_type_option,
            'permission_reminder'=>$this->permission_reminder,
            'visibility'=>$this->visibility,
            'use_archive_bar'=>$this->use_archive_bar
        ];

        return $array;
    }

    public function update()
    {
        $mailchimp = $this->mailchimp;
        /* @var $mailchimp MailChimp */
        $mailchimp->patch("lists/$this->id",$this->createJson());
    }

    /**
     * @param $id
     * @return MailchimpList|null
     */
    public function findOne($id)
    {
        $mailchimp = $this->mailchimp;
        /* @var $mailchimp MailChimp */
        $result = $mailchimp->get("lists/$id");
        if (!empty($result['status']) && $result['status'] == 404) {
            return null;
        }
        return $this->addAttributes($result);
    }

    public function delete($id)
    {
        $mailchimp = $this->mailchimp;
        /* @var $mailchimp MailChimp */
        $result = $mailchimp->delete("lists/$id");
        var_dump($result);exit;
    }

    /**
     * @param $mailchimpResponse
     * @return $this
     */
    public function addAttributes($mailchimpResponse)
    {
        $this->id = $mailchimpResponse['id'];
        $this->name = $mailchimpResponse['name'];
        $this->company = $mailchimpResponse['contact']['company'];
        $this->address1 = $mailchimpResponse['contact']['address1'];
        $this->address2 = $mailchimpResponse['contact']['address2'];
        $this->city = $mailchimpResponse['contact']['city'];
        $this->state = $mailchimpResponse['contact']['state'];
        $this->zip = $mailchimpResponse['contact']['zip'];
        $this->country = $mailchimpResponse['contact']['country'];
        $this->phone = $mailchimpResponse['contact']['phone'];
        $this->from_name = $mailchimpResponse['campaign_defaults']['from_name'];
        $this->from_email = $mailchimpResponse['campaign_defaults']['from_email'];
        $this->subject = $mailchimpResponse['campaign_defaults']['subject'];
        $this->language = $mailchimpResponse['campaign_defaults']['language'];
        $this->notify_on_subscribe = $mailchimpResponse['notify_on_subscribe'];
        $this->notify_on_unsubscribe = $mailchimpResponse['notify_on_unsubscribe'];
        $this->visibility = $mailchimpResponse['visibility'];
        $this->permission_reminder = $mailchimpResponse['permission_reminder'];
        return $this;
    }


    /**
     * @return array
     */
    public function findAll()
    {
        $mailchimp = $this->mailchimp;
        /* @var $mailchimp MailChimp */
        $result = $mailchimp->get("lists");
        $array = [];
        foreach ($result['lists'] as $one) {
            $model = new self;
            $model->addAttributes($one);
            $array[] = $model;
        }
        return $array;
    }



    /**
     * @return array
     */
    public function getVisibility()
    {
        return [
            self::VISIBILITY_PUBLIC => \Yii::t('app','Public'),
            self::VISIBILITY_PRIVATE => \Yii::t('app','Private')
        ];
    }

    public function importMembers(array $users,$list_id)
    {
        $batch = $this->mailchimp->new_batch();
        $operationNumber = 1;
        foreach ($users as $user) {
            if ($user instanceof User) {
                $batch->post("op{$operationNumber}", "lists/$list_id/members", [
                    'email_address' => $user->email,
                    'status'        => 'subscribed',
                ]);
                $operationNumber++;
            }
        }
        return $batch->execute()['id'];
    }

    public function checkBatch($batch_id)
    {
        $batch = $this->mailchimp->new_batch();
        return $batch->check_status($batch_id)['status'];
    }


    public function members()
    {
        $model = $this->mailchimp;
        return $model->get('lists/'.$this->id.'/members');
    }
}