<?php

namespace App\Payments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Payments\payzeAbstract;
use App\Events\OrderReceived;
use App\Models\Order;
use App\Models\Transaction;

use Exception;

class PayzePayment extends payzeAbstract
{
    private Object $processorConfig;
    protected int $payment_type = 5;
    

    private function sendRequest(String $endpoint, String $data, Array $headers, String $method) : Object{
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://payze.io/' . $endpoint,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers
        ]);

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        curl_close($curl);
        
        $result = json_decode($response);

        return (object)[
            'result' => $result,
            'httpcode' => $httpcode
        ];
    }

    protected function validateConfig(object $config) : void
    {   
        if(!isset($config->apiKey) || empty($config->apiKey)) {
            throw new Exception('Payze PAYMENT CONFIG ERROR: No API Key provided.');
        }

        if(!isset($config->apiSecret) || empty($config->apiSecret)) {
            throw new Exception('Payze PAYMENT CONFIG ERROR: No API Secret provided.');
        }
    }  

    protected function prepare(): void
    {
        $this->processorConfig = (object)config('paymentconfigs.payze');
    }

    public function pay(object $args): object
    {
        $tr_id = Transaction::latest()->pluck('id')->first();
        $tr_id = $tr_id + 1;
        $fail_url = 'https://onpay.cloud/callback/'. $tr_id;
        $success_url = 'https://onpay.cloud/callback/'. $tr_id;
        $callback_url = 'https://onpay.cloud/api/payment-callback/'. $args->userId;
        // dd($this->config->amount);
        $data = json_encode([
            "method" => "justPay",
            "apiKey"=> $this->config->apiKey,    
            "apiSecret"=> $this->config->apiSecret,    
            "data" => [
                "amount"=> $this->config->amount,        
                "currency"=> "GEL",
                "callback"=> $success_url,        
                "callbackError" => $fail_url,        
                "hookUrl"=> $callback_url,        
                "preauthorize"=> false,        
                "lang" => "KA",
                "split" => [
                    [
                        "iban" => '' ,
                        "amount" => $this->config->amount / 100 * 5,
                        "payIn" => 0
                    ]
                ]
            ]
        ]);
       
        $headers = ["Content-Type: application/json"];
        $result = $this->sendRequest("api/v1", $data, $headers, "POST");
        $link = $result->result;
        if($result->httpcode !== 200) {
            throw new Exception("Payze PAYMENT REFUND ERROR: Httpcode is not 200, it is: {$result->httpcode}");
        }
        return (object) [
            'redirect' => $link->response->transactionUrl,
            'payId' => $link->response->transactionId,
            // 'amount' => $link->request->amount,
            // 'currency' => $link->request->currency,
        ];
    }


    public function refund(object $args): void
    {
        $headers = ["Content-Type: application/json"];
        $data = json_encode([
            "method"    => "refund",
            "apiKey"    => $this->config->apiKey,    
            "apiSecret" => $this->config->apiSecret, 
            "data"  => [
                "transactionId" => $this->config->payId
            ]
        ]);

        $result = $this->sendRequest("api/v1", $data, $headers, "POST");
        if($result->httpcode !== 200) {
            throw new Exception("Payze PAYMENT REFUND ERROR: Httpcode is not 200, it is: {$result->httpcode}");
        }
    }

    public function get(object $args): object
    {
        $headers = ['Content-Type: application/json'];
        
        $data = json_encode([
            "method"    => "getTransactionInfo",
            "apiKey" => $this->config->apiKey,
            "apiSecret" => $this->config->apiSecret,
            "data"      => [
                "transactionId" => $this->config->payId
            ]
        ]);

        $result = $this->sendRequest("api/v1", $data, $headers, "POST");

        if($result->httpcode !== 200){
            throw new Exception("Payze PAYMENT GET ERROR: Httpcode is not 200, it is {$result->httpcode}");
        }
        
        return $result;
    }

    public function getOrderStatus(object $argument) : object
    {
        if(!$argument) {
            throw new Exception("Payze PAYMENT getOrderStatus ERROR: Order not set!");
        }

        if(!$argument->payId) {
            throw new Exception("Payze PAYMENT getOrderStatus ERROR: transactionId not set!");
        }

        $details = $this->get((object) [
            'transactionId' => $argument->payId
        ]);

        if(!$details) {
            throw new Exception("Payze PAYMENT getOrderStatus ERROR: Unable to retrieve order details");
        }

        if($details->httpcode !== 200) {
            throw new Exception("Payze PAYMENT getOrderStatus ERROR: Httpcode is not 200, it is {$details->httpcode}");
        }
        $result = $details->result->response;

        return (object)[
            'status' => $result->status,
        ];
    }
}
