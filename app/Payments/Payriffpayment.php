<?php


namespace App\Payments;

use Illuminate\Support\Facades\Http;
use App\Models\Transaction;


class Payriffpayment
{

    protected $encryptionToken;
    protected $authorization;
    protected $baseUrl;
    protected $header;

    public const SUCCESS = "00000";
    public const WARNING = "01000";
    public const ERROR = "15000";
    public const INVALID_PARAMETERS = "15400";
    public const UNAUTHORIZED = "14010";
    public const TOKEN_NOT_PRESENT = "14013";
    public const INVALID_TOKEN = "14014";

    public function __construct($orderId =null, $sessionId = null)
    {
        $this->merchantSecretKey = config('app.payriff_secret');
        // $this->merchantUniqueNumber = config('app.payriff_number');
        $this->encryptionToken = time() . rand();
        $this->authorization = sha1($this->merchantSecretKey . $this->encryptionToken);
        $this->baseUrl = "https://api.payriff.com/api/v1/";
        $this->orderId = $orderId;
        $this->sessionId = $sessionId;
    }

    public function setOrderId($value)
    {
        $this->orderId = $value;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setSessionId($value)
    {
        $this->sessionId = $value;
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }

    public function sendRequest($method, $body, $merchantUniqueNumber)
    {
        return 
            Http::withHeaders([
                "Authorization" => $this->authorization,
            ])->post($this->baseUrl.$method, [ 
                "body" => $body,
                "encryptionToken" => $this->encryptionToken,
                "merchant" => $merchantUniqueNumber
            ])->object();
    }

    /**
     * @method createOrder
     *
     * @param  $amount          Payment amount
     * @param  $approveURL      After successful payment, the forwarding address
     * @param  $cancelURL	    After canceled payment, the forwarding address
     * @param  $declineURL	    After declined payment, the forwarding address
     * @param  $currencyType	Currency of payment (AZN, USD, EUR)
     * @param  $description	    Payment description for customer
     * @param  $language	    Payment page language (AZ, EN, RU)
     * 
     * @return string           Payment URL
     */
    public function createOrder($amount, $description = null, $currencyType = 'AZN',  $language = 'AZ', $merchantSecretKey = null, $merchantUniqueNumber = null, $approveURL = null, $cancelURL = null, $declineURL = null)
    {
        $tr_id = Transaction::latest()->pluck('id')->first();
        $tr_id = $tr_id + 1;

        $this->merchantSecretKey = $merchantSecretKey;
        $this->merchantUniqueNumber = $merchantUniqueNumber;

        $body = [
            "amount" => $amount,
            "approveURL"=> 'https://onpay.cloud/callback/'. $tr_id,
            "cancelURL" => 'https://onpay.cloud/callback/'. $tr_id,
            "currencyType" => $currencyType,
            "declineURL" => 'https://onpay.cloud/callback/'. $tr_id,
            "description" => $description,
            "language" => $language
        ];
        $uniqueNumber = $merchantUniqueNumber;
        $response = $this->sendRequest('createOrder', $body, $uniqueNumber);
        if($response->code == $this::SUCCESS){
            $this->setOrderId($response->payload->orderId);
            $this->setSessionId($response->payload->sessionId);
            
            return $response->payload->paymentUrl;
        }

        
        return route('');
    }

    
    /**
     * @method getOrderInformation
     *
     * @param  $language	    Payment page language (AZ, EN, RU)
     * @param  $orderId	        After a successful createOrder request, in response you will receive an orderId
     * @param  $sessionId	    After a successful createOrder request, in response you will receive an
     * 
     * @return object           Order Information
     */
    public function getOrderInformation($language = 'AZ', $orderId = null, $sessionId = null)
    {
        $body = [
            "languageType" => $language,
            "orderId"=> $orderId ?? $this->orderId,
            "sessionId" => $sessionId ?? $this->sessionId,
        ];
        
        $response = $this->sendRequest('getOrderInformation', $body);
        
        if($response->code == $this::SUCCESS){
            return $response->payload;
        }
        
        return false;
    }


    /**
     * @method getStatusOrder
     *
     * @param  $language	    Payment page language (AZ, EN, RU)
     * @param  $orderId	        After a successful createOrder request, in response you will receive an orderId
     * @param  $sessionId	    After a successful createOrder request, in response you will receive an
     * 
     * @return string           Order Status
     */
    public function getStatusOrder($language = 'AZ', $orderId = null, $sessionId = null, $merchantUniqueNumber = null)
    {
        $uniqueNumber = $merchantUniqueNumber;
        $body = [
            "language" => $language,
            "orderId"=> $orderId,
            "sessionId" => $sessionId,
        ];

        $response = $this->sendRequest('getStatusOrder', $body, $uniqueNumber);
        if($response->code == $this::SUCCESS){
            return $response->payload->orderStatus;
        }
        
        return $response->code;
    }


    /**
     * @method refund
     *
     * @param  $refundAmount	Refund amount or part of the amount
     * @param  $orderId	        After a successful createOrder request, in response you will receive an orderId
     * @param  $sessionId	    After a successful createOrder request, in response you will receive an
     * 
     * @return string           Response message
     */
    public function refund($refundAmount, $orderId = null, $sessionId = null, $merchantUniqueNumber = null)
    {
        $uniqueNumber = $merchantUniqueNumber;
        $body = [
            "refundAmount" => $refundAmount,
            "orderId"=> $orderId ?? $this->orderId,
            "sessionId" => $sessionId ?? $this->sessionId,
        ];
        $response = $this->sendRequest('refund', $body, $uniqueNumber);
        
        return $response->internalMessage;
    }


    /**
     * @method preAuth
     *
     * @param  $amount          Payment amount
     * @param  $approveURL      After successful payment, the forwarding address
     * @param  $cancelURL	    After canceled payment, the forwarding address
     * @param  $declineURL	    After declined payment, the forwarding address
     * @param  $currencyType	Currency of payment (AZN, USD, EUR)
     * @param  $description	    Payment description for customer
     * @param  $language	    Payment page language (AZ, EN, RU)
     * 
     * @return string           Payment URL
     */
    public function preAuth($amount, $description = null, $currencyType = 'AZN',  $language = 'AZ', $approveURL = null, $cancelURL = null, $declineURL = null)
    {
        $body = [
            "amount" => $amount,
            "approveURL"=> $approveURL,
            "cancelURL" => $cancelURL,
            "currencyType" => $currencyType,
            "declineURL" => $declineURL,
            "description" => $description,
            "language" => $language
        ];

        $response = $this->sendRequest('preAuth', $body);
       
        if($response->code == $this::SUCCESS){
            $this->setOrderId($response->payload->orderId);
            $this->setSessionId($response->payload->sessionId);
            
            return $response->payload->paymentUrl;
        }

        return route('redirectionFailed');
    }


    /**
     * @method reverse
     *
     * @param  $amount          Payment amount
     * @param  $description	    Payment description for customer
     * @param  $language	    Payment page language (AZ, EN, RU)
     * @param  $orderId	        After a successful createOrder request, in response you will receive an orderId
     * @param  $sessionId	    After a successful createOrder request, in response you will receive an
     * 
     * @return string           Response message
     */
    public function reverse($amount, $description = null, $language = 'AZ', $orderId = null, $sessionId = null)
    {
        $body = [
            "amount" => $amount,
            "description" => $description,
            "language" => $language,
            "orderId"=> $orderId ?? $this->orderId,
            "sessionId" => $sessionId ?? $this->sessionId,
        ];

        $response = $this->sendRequest('reverse', $body);
        
        return $response->internalMessage;
    }


    /**
     * @method completeOrder
     *
     * @param  $amount          Payment amount
     * @param  $description	    Payment description for customer
     * @param  $language	    Payment page language (AZ, EN, RU)
     * @param  $orderId	        After a successful createOrder request, in response you will receive an orderId
     * @param  $sessionId	    After a successful createOrder request, in response you will receive an
     * 
     * @return string           Response message
     */
    public function completeOrder($amount, $description = null, $language = 'AZ', $orderId = null, $sessionId = null)
    {
        $body = [
            "amount" => $amount,
            "description" => $description,
            "language" => $language,
            "orderId"=> $orderId ?? $this->orderId,
            "sessionId" => $sessionId ?? $this->sessionId,
        ];

        $response = $this->sendRequest('completeOrder', $body);
        
        return $response->internalMessage;
    }


    /**
     * @method cardSave
     *
     * @param  $amount          Payment amount
     * @param  $approveURL      After successful payment, the forwarding address
     * @param  $cancelURL	    After canceled payment, the forwarding address
     * @param  $declineURL	    After declined payment, the forwarding address
     * @param  $currencyType	Currency of payment (AZN, USD, EUR)
     * @param  $description	    Payment description for customer
     * @param  $language	    Payment page language (AZ, EN, RU)
     * 
     * @return string           Payment URL
     */
    public function cardSave($amount, $description = null, $currencyType = 'AZN',  $language = 'AZ', $approveURL = null, $cancelURL = null, $declineURL = null)
    {
        $body = [
            "amount" => $amount,
            "approveURL"=> $approveURL,
            "cancelURL" => $cancelURL,
            "currencyType" => $currencyType,
            "declineURL" => $declineURL,
            "description" => $description,
            "language" => $language
        ];

        $response = $this->sendRequest('cardSave', $body);
       
        if($response->code == $this::SUCCESS){
            $this->setOrderId($response->payload->orderId);
            $this->setSessionId($response->payload->sessionId);
            
            return $response->payload->paymentUrl;
        }

        return route('redirectionFailed');
    }


    /**
     * @method autoPay
     *
     * @param  $amount          Payment amount
     * @param  $cardUuid	    Unique id credit card payment for automatic payment. 
     *                          If you want to save card payment details. You can get it using the cardSave method.
     * @param  $description	    Payment description for customer
     * 
     * @return string           Payment URL
     */
    public function autoPay($amount, $cardUuid, $description = null)
    {
        $body = [
            "amount" => $amount,
            "cardUuid" => $cardUuid,
            "description" => $description,
        ];

        $response = $this->sendRequest('autoPay', $body);
        
        return $response->internalMessage;
    }

}