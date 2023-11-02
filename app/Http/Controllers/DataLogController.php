<?php

namespace App\Http\Controllers;

class DataLogController extends DataRequestController
{
    function createCustomerLogData($userData, $ip, $source, $isRegistered)
    {

        $logTime = date("Y-m-d H:i:s");
        $cli = substr($userData['cli'], -10);
        $customerJourneyData = array($cli, VIVR_MODULE_TYPE, VIVR_ONLY_MODULE_SUBTYPE, $logTime, $userData['session_id']);
        $customerLogData = array($logTime, $logTime, $cli, $userData['did'], $userData['ivr_id'], 0, $userData['session_id'],
        $userData['language'], '', '', '', $source, $ip, $isRegistered);


        $customerJourneyResponse = $this->storeCustomerJourney($customerJourneyData);
        $logFromWebVisitResponse = $this->storeVivrLog($customerLogData);
        if ($customerJourneyResponse && $logFromWebVisitResponse) {
            return true;
        }
        return false;
    }

    private function storeCustomerJourney($paramsArray)
    {
        $this->params = json_encode($paramsArray);
        $this->method = STORE_CUSTOMER_JOURNEY_FUNCTION;
       $responseData= $this->getResponse($this->method,$this->params);

        if ($responseData == 1) {

            return true;
        }
        return false;
    }

    private function storeVivrLog($paramsArray)
    {
        $this->params = json_encode($paramsArray);
        $this->method = VIVR_LOG_FUNCTION;
       $responseData=$this->getResponse($this->method ,$this->params);
//        if ($this->responseData == 1) {
//            return true;
//        }
        return true;
    }

    public function updateVivrLog($stopTime, $timeInIvr, $sessionId)
    {
        $this->params = json_encode(array($stopTime, $timeInIvr, $sessionId));
        $this->method = UPDATE_VIVR_LOG;
        $this->getResponse();
        if ($this->responseData == 1) {
            return true;
        }
        return false;
    }

    public function logCustomerJourney($logParams)
    {
        $this->params = json_encode(array($logParams['logTime'], $logParams['fromPage'], $logParams['toPage'],
            $logParams['sessionId'], $logParams['ivrId'], $logParams['dtmf'], $logParams['timeInIvr'],
            $logParams['statusFlag'], $logParams['ip'], $logParams['moduleType'], $logParams['tpStatus']));
        $this->method = LOG_VIVR_JOURNEY;
        $this->getResponse();
        if ($this->responseData == 1) {
            return true;
        }
        return false;
    }

    public function storeCustomerFeedback($stopTime, $timeInIvr, $sessionId, $feedback)
    {
        $this->params = json_encode(array($stopTime, $timeInIvr, $sessionId, $feedback));
        $this->method = SAVE_CUSTOMER_FEEDBACK;
        $this->getResponse();
        if ($this->responseData == 1) {
            return true;
        }
        return false;
    }
}
