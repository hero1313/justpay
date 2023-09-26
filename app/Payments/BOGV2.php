<?php

namespace App\Helpers\Payment;

use App\Helpers\Payment\Payment;

use App\Order;

use Exception;


class BOGV2 extends Payment{

    private Object $processorConfig;
    private String $requestToken;
    protected Int $payment_type = 3;

    private function sendRequest(String $endpoint, String $data, Array $headers, String $method, $authorization = false) : Object {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.bog.ge/payments/v1/' . $endpoint,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
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
        if(!isset($config->client_id) || empty($config->client_id)) {
            throw new Exception('BOGV2 CONFIG ERROR: No client_id provided.');
        }

        if(!isset($config->secret_key) || empty($config->secret_key)) {
            throw new Exception('BOGV2 CONFIG ERROR: No secret_key provided.');
        }
    } 

    protected function prepare() : void
    {
        $this->processorConfig = (object)config('paymentconfigs.bogv2');
        $basic = base64_encode("{87554}:{x0okDuKMmxi5}");
        
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: ' . 'Basic ' . $basic
        ];
        
        $body = 'grant_type=client_credentials';
        $tokenRequest = $this->sendRequest('', $body, $headers, 'POST', true);

        if(!$tokenRequest){
            throw new Exception('BOGV2 PREPARE ERROR: Unable to get an access token.');
        }

        $this->requestToken = $tokenRequest->result->token_type . " " . $tokenRequest->result->access_token;
    }

    private function formBasket($cart) : array {
        $basket = [];
        foreach ($cart as $C) {
            $productId = $C['id'];
            $existingProduct = null;
           
            if (sizeof($basket) != 0) {
                foreach ($basket as &$item) {
                    if ($item['product_id'] === $productId) {
                        $existingProduct = $item;
                        $item['quantity'] += $C['amount'];
                        break;
                    }
                }
            }
    
            if ($existingProduct === null) {
                $basket[] = [
                    "quantity" => $C['amount'],
                    "unit_price" => $C['price'] / $C['amount'],
                    "product_id" => $productId
                ];
            } 
        }
        
        return $basket;
    }

    public function pay(object $args) : object
    {   
        $redirect = "redirect.com";
       
        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . $this->requestToken,
            "Accept-Language: ka"
        ];
        
        $basket =  $this->formBasket($args->cart);
        
        $data = json_encode([
            "callback_url" => "callback.com",
            "external_order_id"=> $args->order_id,
            'capture' => 'automatic',
            "purchase_units"=> [
                "currency"=> "GEL",
                "basket"=> $basket,
                'total_amount' => $args->amount,
            ],
            "redirect_urls" => [
                "fail"=> $redirect,
                "success"=> $redirect
            ]
            // 'payment_method' => ['card', 'google_pay', 'bnpl']
        ]);
    
        $response = $this->sendRequest('ecommerce/orders', $data, $headers, 'POST');
            
       
        $result = $response->result;
        if(!isset($result->_links)){
            throw new Exception($result->message);
        }

        $links = $result->_links;
       
        return (object)[
            'redirect' => $links->redirect->href,
            'orderid' => $result->id
        ];
    }

    public function refund(object $args) : void
    {
        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . $this->requestToken
        ];
        $data = json_encode([]);
        $result = $this->sendRequest("payment/refund/{$args->order_id}", "{}", $headers, "POST");
        
        if($result->httpcode !== 202) {
            throw new Exception("BOGV2 REFUND ERROR: Httpcode is not 202, it is: {$result->httpcode}");
        }
    }

    public function get(object $args) : object
    {
        $headers = [
            // 'Content-Type: application/json',
            'Authorization: ' . $this->requestToken
        ];
        
        $result = $this->sendRequest("receipt/{$args->order_id}", "{}", $headers, "GET");
        
        if($result->httpcode !== 200){
            throw new Exception("BOGV2 GET ERROR: Httpcode is not 200, it is {$result->httpcode}");
        }
        
        return $result;
    }

    public function getOrderStatus(Order $order) : object 
    {   
        if(!$order) {
            throw new Exception("BOGV2 getOrderStatus ERROR: Order not set!");
        }

        if(!$order->transaction_id) {
            throw new Exception("BOGV2 getOrderStatus ERROR: payment_hash not set!");
        }

        $details = $this->get((object) [
            'order_id' => $order->transaction_id
        ]);
        
        if(!$details) {
            throw new Exception("BOGV2 getOrderStatus ERROR: Unable to retrieve order details");
        }

        if($details->httpcode !== 200) {
            throw new Exception("BOGV2 getOrderStatus ERROR: Httpcode is not 200, it is {$details->httpcode}");
        }
        
        $response = $details->result;
        $status = 0;
        switch($response->order_status->key){
            case 'completed':
                $status = 1;
                break;
            case 'refunded':
                $status = -1;
                break;
            case 'rejected':
                $status = -2;
                break;
        }
        
        return (object)[
            'status' => $status,
            'data' => $response->order_status->key,
            'success' => $status === 1 ? true : false
        ];
    }
}
