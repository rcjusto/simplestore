<?php

namespace app\components;

use app\models\Property;
use yii\helpers\Html;

class AuthorizeNetCIM 
{

    const RESULT_SUCCESS = 'success';
    const RESULT_ERROR = 'error';

    public $apiHost = "apitest.authorize.net";
    public $apiPath = "/xml/v1/request.api";
    public $apiMode = "testMode";

    public $loginName;
    public $transactionKey;
    public $_lastError;

    /**
     * AuthorizeNetCIM constructor.
     */
    public function __construct()
    {
        $this->loginName = Property::getPropertyValue('authorize_login');
        $this->transactionKey = Property::getPropertyValue('authorize_key');
         if (Property::getPropertyValue('authorize_live','no')=='yes') {
             $this->apiHost = 'api.authorize.net';
             $this->apiMode = 'liveMode';
         }
    }

    public function createProfile($customerId, $email)
    {
        $content =
            "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
            "<createCustomerProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
            $this->getMerchantAuthenticationBlock() .
            "<profile>" .
            "<merchantCustomerId>" . $customerId . "</merchantCustomerId>" .
            "<description></description>" .
            "<email>" . $email . "</email>" .
            "</profile>" .
            "</createCustomerProfileRequest>";
        $response = $this->sendXmlRequest($content);
        $parsedResponse = $this->parseApiResponse($response);
        if ("Ok" == $parsedResponse->messages->resultCode) {
            return array("result" => self::RESULT_SUCCESS, "customerProfileId" => htmlspecialchars($parsedResponse->customerProfileId));
        }
        return array("result" => self::RESULT_ERROR, "code"=> $parsedResponse->messages->message->code, "message" => htmlspecialchars($parsedResponse->messages->message->text));
    }

    public function updateProfile($profileID, $customerId, $email)
    {
        $content =
            "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
            "<updateCustomerProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
            $this->getMerchantAuthenticationBlock() .
            "<profile>" .
            "<merchantCustomerId>" . $customerId . "</merchantCustomerId>" .
            "<description></description>" .
            "<email>" . $email . "</email>" .
            "<customerProfileId>" . $profileID . "</customerProfileId>" .
            "</profile>" .
            "</updateCustomerProfileRequest>";

        $response = $this->sendXmlRequest($content);
        $parsedResponse = $this->parseApiResponse($response);
        if ("Ok" == $parsedResponse->messages->resultCode) {
            return array("result" => self::RESULT_SUCCESS);
        }
        return array("result" => self::RESULT_ERROR, "message" => htmlspecialchars($parsedResponse->messages->message->text));
    }

    public function deleteProfile($profileId)
    {
        $content =
            "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
            "<deleteCustomerProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
            $this->getMerchantAuthenticationBlock() .
            "<customerProfileId>" . $profileId . "</customerProfileId>" .
            "</deleteCustomerProfileRequest>";
        $response = $this->sendXmlRequest($content);
        $parsedResponse = $this->parseApiResponse($response);
        if (!is_null($parsedResponse) && "Ok" == $parsedResponse->messages->resultCode) {
            return true;
        }
        return false;
    }

    public function createPaymentProfile($profileId, $companyName, $firstName, $lastName, $phone, $cc, $ed, $ccv = null, $address = null, $city = null, $state = null, $zip = null)
    {
        $content =
            "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
            "<createCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
            $this->getMerchantAuthenticationBlock() .
            "<customerProfileId>" . Html::encode(strip_tags($profileId)) . "</customerProfileId>" .
            "<paymentProfile>" .
            "<billTo>" .
            "<firstName>" . Html::encode(strip_tags($firstName)) . "</firstName>" .
            "<lastName>" . Html::encode(strip_tags($lastName)) . "</lastName>" .
            "<company>" . Html::encode(strip_tags($companyName)) . "</company>" .
            ((!empty($address)) ? "<address>" . Html::encode(strip_tags($address)) . "</address>" : '') .
            ((!empty($city)) ? "<city>" . Html::encode(strip_tags($city)) . "</city>" : '') .
            ((!empty($state)) ? "<state>" . Html::encode(strip_tags($state)) . "</state>" : '') .
            ((!empty($zip)) ? "<zip>" . Html::encode(strip_tags($zip)) . "</zip>" : '') .
            "<country>US</country>" .
            "<phoneNumber>" . Html::encode(strip_tags($phone)) . "</phoneNumber>" .
            "</billTo>" .
            "<payment>" .
            "<creditCard>" .
            "<cardNumber>" . Html::encode(strip_tags($cc)) . "</cardNumber>" .
            "<expirationDate>" . Html::encode(strip_tags($ed)) . "</expirationDate>" . // required format for API is YYYY-MM
            ((!empty($ccv)) ? "<cardCode>" . Html::encode(strip_tags($ccv)) . "</cardCode>" : '') .
            "</creditCard>" .
            "</payment>" .
            "</paymentProfile>" .
            "<validationMode>" . $this->apiMode . "</validationMode>" . // or testMode
            "</createCustomerPaymentProfileRequest>";

        $response = $this->sendXmlRequest($content);
        $parsedResponse = $this->parseApiResponse($response);

        if ("Ok" == $parsedResponse->messages->resultCode) {
            return array("result" => self::RESULT_SUCCESS, "customerPaymentProfileId" => htmlspecialchars($parsedResponse->customerPaymentProfileId));
        }
        return array("result" => self::RESULT_ERROR, "message" => htmlspecialchars($parsedResponse->messages->message->text . ' (' . $parsedResponse->messages->message->code . ')'));
    }

    public function updatePaymentProfile($profileId, $paymentId, $firstName, $lastName, $phone, $cc, $ed, $ccv = null, $address = null, $city = null, $state = null, $zip = null)
    {
        $content =
            "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
            "<updateCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
            $this->getMerchantAuthenticationBlock() .
            "<customerProfileId>" . Html::encode(strip_tags($profileId)) . "</customerProfileId>" .
            "<paymentProfile>" .
            "<billTo>" .
            "<firstName>" . Html::encode(strip_tags($firstName)) . "</firstName>" .
            "<lastName>" . Html::encode(strip_tags($lastName)) . "</lastName>" .
            ((!empty($address)) ? "<address>" . Html::encode(strip_tags($address)) . "</address>" : '') .
            ((!empty($city)) ? "<city>" . Html::encode(strip_tags($city)) . "</city>" : '') .
            ((!empty($state)) ? "<state>" . Html::encode(strip_tags($state)) . "</state>" : '') .
            ((!empty($zip)) ? "<zip>" . Html::encode(strip_tags($zip)) . "</zip>" : '') .
            "<country>US</country>" .
            "<phoneNumber>" . Html::encode(strip_tags($phone)) . "</phoneNumber>" .
            "</billTo>" .
            "<payment>" .
            "<creditCard>" .
            "<cardNumber>" . Html::encode(strip_tags($cc)) . "</cardNumber>" .
            "<expirationDate>" . Html::encode(strip_tags($ed)) . "</expirationDate>" . // required format for API is YYYY-MM
            ((!empty($ccv)) ? "<cardCode>" . Html::encode(strip_tags($ccv)) . "</cardCode>" : '') .
            "</creditCard>" .
            "</payment>" .
            "<customerPaymentProfileId>" . $paymentId . "</customerPaymentProfileId>" .
            "</paymentProfile>" .
            "<validationMode>" . $this->apiMode . "</validationMode>" . // or testMode
            "</updateCustomerPaymentProfileRequest>";

        $response = $this->sendXmlRequest($content);
        $parsedResponse = $this->parseApiResponse($response);

        if ("Ok" == $parsedResponse->messages->resultCode) {
            return array("result" => self::RESULT_SUCCESS, "customerPaymentProfileId" => htmlspecialchars($parsedResponse->customerPaymentProfileId));
        }
        return array("result" => self::RESULT_ERROR, "message" => htmlspecialchars($parsedResponse->messages->message->text));
    }

    public function deletePaymentProfile($profileId, $paymentId)
    {
        $content =
            "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
            "<deleteCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
            $this->getMerchantAuthenticationBlock() .
            "<customerProfileId>" . Html::encode(strip_tags($profileId)) . "</customerProfileId>" .
            "<customerPaymentProfileId>" . Html::encode(strip_tags($paymentId)) . "</customerPaymentProfileId>" .
            "</deleteCustomerPaymentProfileRequest>";
        $response = $this->sendXmlRequest($content);
        $parsedResponse = $this->parseApiResponse($response);
        if (!is_null($parsedResponse) && "Ok" == $parsedResponse->messages->resultCode) {
            return true;
        }
        return false;
    }

    public function getPaymentProfileInfo($profileId, $paymentId)
    {
        $content =
            "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
            "<getCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
            $this->getMerchantAuthenticationBlock() .
            "<customerProfileId>" . Html::encode(strip_tags($profileId)) . "</customerProfileId>" .
            "<customerPaymentProfileId>" . Html::encode(strip_tags($paymentId)) . "</customerPaymentProfileId>" .
            "</getCustomerPaymentProfileRequest>";
        $response = $this->sendXmlRequest($content);
        $parsedResponse = $this->parseApiResponse($response);
        if ("Ok" == $parsedResponse->messages->resultCode) {
            return array("result" => self::RESULT_SUCCESS, "paymentProfile" => $parsedResponse->paymentProfile);
        }
        return array("result" => self::RESULT_ERROR, "message" => htmlspecialchars($parsedResponse->messages->message->text));

    }

    public function authorizeAndCapture($profileId, $paymentId, $amount, $refID = "")
    {
        $content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
            "<createCustomerProfileTransactionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
            $this->getMerchantAuthenticationBlock() .
            (!empty($refID) ? "<refId>$refID</refId>" : "") .
            "<transaction>" .
            "<profileTransAuthCapture>" .
            "<amount>" . number_format($amount, 2, '.', '') . "</amount>" .
            "<customerProfileId>" . Html::encode(strip_tags($profileId)) . "</customerProfileId>" .
            "<customerPaymentProfileId>" . Html::encode(strip_tags($paymentId)) . "</customerPaymentProfileId>" .
            "</profileTransAuthCapture>" .
            "</transaction>" .
            "</createCustomerProfileTransactionRequest>";
        $response = $this->sendXmlRequest($content);
        $parsedResponse = $this->parseApiResponse($response);
        if ("Ok" == $parsedResponse->messages->resultCode) {

            $directResponseFields = explode(",", $parsedResponse->directResponse);
            $responseCode = $directResponseFields[0]; // 1 = Approved 2 = Declined 3 = Error
            $responseReasonCode = $directResponseFields[2]; // See http://www.authorize.net/support/AIM_guide.pdf
            $responseReasonText = $directResponseFields[3];
            $approvalCode = $directResponseFields[4]; // Authorization code
            $transId = $directResponseFields[6];

            if ("1" == $responseCode) {
                return array(
                    "result" => self::RESULT_SUCCESS,
                    "transId" => $transId,
                    "responseCode" => $responseCode,
                    "approvalCode" => $approvalCode,
                    "responseReasonText" => $responseReasonText,
                );
            } else {
                return array(
                    "result" => self::RESULT_ERROR,
                    "transId" => $transId,
                    "responseCode" => $responseCode,
                    "responseReasonCode" => $responseReasonCode,
                    "responseReasonText" => $responseReasonText,
                );
            }

        } else {

            $directResponseFields = isset($parsedResponse->directResponse) && !is_null($parsedResponse->directResponse) ? explode(",", $parsedResponse->directResponse) : array();
            if (count($directResponseFields) > 6) {
                $responseCode = $directResponseFields[0]; // 1 = Approved 2 = Declined 3 = Error
                $responseReasonCode = $directResponseFields[2]; // See http://www.authorize.net/support/AIM_guide.pdf
                $responseReasonText = $directResponseFields[3];
                $transId = $directResponseFields[6];
                return array(
                    "result" => self::RESULT_ERROR,
                    "transId" => $transId,
                    "responseCode" => $responseCode,
                    "responseReasonCode" => $responseReasonCode,
                    "responseReasonText" => $responseReasonText,
                );
            } else {
                $responseReasonCode = !is_null($parsedResponse->messages) && !is_null($parsedResponse->messages->message) && !is_null($parsedResponse->messages->message->code) ? '' . $parsedResponse->messages->message->code : '---';
                $responseReasonText = !is_null($parsedResponse->messages) && !is_null($parsedResponse->messages->message) && !is_null($parsedResponse->messages->message->text) ? '' . $parsedResponse->messages->message->text : "Unknown Error";
                return array(
                    "result" => self::RESULT_ERROR,
                    "transId" => "",
                    "responseCode" => "",
                    "responseReasonCode" => $responseReasonCode,
                    "responseReasonText" => $responseReasonText,
                );
            }
        }
    }

    private function sendXmlRequest($content)
    {
        return $this->sendRequestViaFsockopen($this->apiHost, $this->apiPath, $content);
    }

    private function sendRequestViaFsockopen($host, $path, $content)
    {
        $postUrl = "ssl://" . $host;
        $header = "Host: $host\r\n";
        $header .= "User-Agent: PHP Script\r\n";
        $header .= "Content-Type: text/xml\r\n";
        $header .= "Content-Length: " . strlen($content) . "\r\n";
        $header .= "Connection: close\r\n\r\n";
        $fp = fsockopen($postUrl, 443, $errno, $errstr, 30);
        if (!$fp) {
            $body = false;
        } else {
            $out = '';
            error_reporting(E_ERROR);
            fputs($fp, "POST $path  HTTP/1.1\r\n");
            fputs($fp, $header . $content);
            fwrite($fp, $out);
            $response = "";
            while (!feof($fp)) {
                $response = $response . fgets($fp, 128);
            }
            fclose($fp);
            error_reporting(E_ALL ^ E_NOTICE);

            $len = strlen($response);
            $bodyPos = strpos($response, "\r\n\r\n");
            if ($bodyPos <= 0) {
                $bodyPos = strpos($response, "\n\n");
            }
            while ($bodyPos < $len && $response[$bodyPos] != '<') {
                $bodyPos++;
            }
            $body = substr($response, $bodyPos);
        }
        return $body;
    }

    private function sendRequestViaCurl($host, $path, $content)
    {
        $postUrl = "https://" . $host . $path;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $postUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        return $response;
    }


    function parseApiResponse($content)
    {
        $this->_lastError = '';
        $parsedResponse = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOWARNING);
        if ("Ok" != $parsedResponse->messages->resultCode) {
            foreach ($parsedResponse->messages->message as $msg) {
                $this->_lastError .= "[" . htmlspecialchars($msg->code) . "] " . htmlspecialchars($msg->text) . "<br>";
            }
        }
        return $parsedResponse;
    }

    private function getMerchantAuthenticationBlock()
    {
        return "<merchantAuthentication>" .
        "<name>" . $this->loginName . "</name>" .
        "<transactionKey>" . $this->transactionKey . "</transactionKey>" .
        "</merchantAuthentication>";
    }


} 