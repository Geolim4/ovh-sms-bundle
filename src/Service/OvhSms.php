<?php
/**
 * Created by PhpStorm.
 * User: cyril
 * Date: 23/07/15
 * Time: 17:07
 */

namespace A2zi\OvhSmsBundle\Service;


use Ovh\Api;
use Ovh\Exceptions\InvalidParameterException;


class OvhSms {
    /** @var  string */
    private $sender;

    /** @var  string */
    private $applicationKey;

    /** @var  string */
    private $applicationSecret;

    /** @var  string */
    private $consumerKey;

    /** @var  string */
    private $smsService;

    /** @var  string */
    private $endpoint;

    /** @var  array */
    private $sms = [];



    public function __construct($sender, $applicationKey, $applicationSecret, $consumerKey, $smsService, $endpoint ){
        $this->sender = $sender;
        $this->applicationKey = $applicationKey;
        $this->applicationSecret = $applicationSecret;
        $this->consumerKey = $consumerKey;
        $this->smsService = $smsService;
        $this->endpoint = $endpoint;
        $this->initializeMessage();
    }


    /**
     * @return array
     * @throws InvalidParameterException
     */
    public function send(){


        if( empty($this->sms['message']) || empty($this->sms['receivers'])){
            throw new InvalidParameterException('Message or Receivers are empty');
        }

        $connexion = $this->getConnexion();
        $content = (object) $this->sms;

        if(empty($this->smsService)){
            $smsService = $this->getDefaultSmsService();
        }
        else{
            $smsService = $this->smsService;
        }

        $resultPostJob = $connexion->post('/sms/'. $smsService . '/jobs/', $content);

        return $resultPostJob;
    }


    public function getDefaultSmsService(){
        $connexion = $this->getConnexion();
        $smsServices = $connexion->get('/sms/');
        $defaultService = null;
        if(is_array($smsServices) && !empty($smsServices)){
            $defaultService = reset($smsServices);
        }
        return $defaultService;
    }

    /**
     * Initialize Sms
     * @return $this
     */
    public function initializeMessage(){

        if(!empty($this->sender)){
            $this->setSmsParam('sender',$this->sender);
        }

        $this
            ->setSmsParam('charset','UTF-8')
            ->setClass('phoneDisplay')
            ->set7BitCoding()
            ->setNoStopClause(true)
            ->setPriority('high')
            ->setSenderForResponse(false)
            ->setValidityPeriod(2880);

        return $this;
    }

    public function getSmsParam($key,$default=null){
        return isset($this->sms[$key])?$this->sms[$key]:$default;
    }

    public function setSmsParam($key,$value){
        $this->sms[$key] = $value;
        return $this;
    }

    public function setClass($class){
        if(in_array($class,['phoneDisplay','flash','sim','toolkit'])){
            $this->setSmsParam('class',$class);
        }
        else{
            throw new InvalidParameterException('Invalid $class argument (accepted values are "phoneDisplay", "flash", "sim", and "toolkit"');
        }
        return $this;
    }

    public function set7BitCoding(){
        $this->setSmsParam('coding','7bit');
        return $this;
    }
    public function set8BitCoding(){
        $this->setSmsParam('coding','8bit');
        return $this;
    }


    public function setMessage($message){
        $this->setSmsParam('message',(string)$message);
        return $this;
    }

    public function setNoStopClause($hideStopClause = true){
        $this->setSmsParam('noStopClause', boolval($hideStopClause));
        return $this;
    }

    public function setSenderForResponse($senderForResponse = true){
        $this->setSmsParam('senderForResponse', boolval($senderForResponse));
        return $this;
    }

    public function setValidityPeriod($minuteNumber){
        $this->setSmsParam('validityPeriod', intval($minuteNumber));
        return $this;
    }

    public function setPriority($priority = 'high'){
        if(in_array($priority,['high','low','medium','veryLow'])){
            $this->setSmsParam('priority', $priority);
        }
        else{
            throw new InvalidParameterException('Invalid $priority argument (accepted values are "high", "low", "medium", and "veryLow"');
        }
        return $this;
    }


    public function addReceiver($phoneNumber){
        $receivers = $this->getSmsParam('receivers',[]);
        $receivers[] = $this->formatPhoneNumber($phoneNumber);
        $this->setSmsParam('receivers',$receivers);
        return $this;
    }

    public function setRecievers(array $phoneNumbers){
        // reset "recievers" array
        $this->setSmsParam('receivers',[]);
        foreach($phoneNumbers as $phoneNumber){
            $this->addReceiver($phoneNumber);
        }
        return $this;
    }

    private function formatPhoneNumber($phoneNumber){
        return preg_replace('/([^0-9\+])/','',$phoneNumber);
    }

    /**
     * @return Api
     */
    public function getConnexion(){
        /* \Ovh\Api class annotations are poorly formed  :-( */
        /** @noinspection PhpParamsInspection */
        return new Api($this->applicationKey, $this->applicationSecret, $this->endpoint, $this->consumerKey);
    }




}