<?php

namespace App\Payments;

use App\Payments\Payment;
use App\Models\Transaction;

use Exception;

class BOGpayment extends Payment
{
    private Object $processorConfig;
    private String $requestToken;
    protected Int $payment_type = 3;

    private function sendRequest(String $endpoint, String $data, array $headers, String $method): Object
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://ipay.ge/opay/api/v1/' . $endpoint,
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

    protected function validateConfig(object $config): void
    {
        if (!isset($config->client_id) || empty($config->client_id)) {
            throw new Exception('BOG PAYMENT CONFIG ERROR: No client_id provided.');
        }
        if (!isset($config->secret_key) || empty($config->secret_key)) {
            throw new Exception('BOG PAYMENT CONFIG ERROR: No secret_key provided.');
        }
    }

    protected function prepare(): void
    {
        $this->processorConfig = (object)config('paymentconfigs.bog');
        $basic = base64_encode("{$this->config->client_id}:{$this->config->secret_key}");
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: ' . 'Basic ' . $basic
        ];
        $body = 'grant_type=client_credentials';
        $tokenRequest = $this->sendRequest("oauth2/token", $body, $headers, "POST");
        if (!$tokenRequest) {
            throw new Exception('BOG PAYMENT PREPARE ERROR: Unable to get an access token.');
        }
        $this->requestToken = $tokenRequest->result->token_type . " " . $tokenRequest->result->access_token;
    }

    public function createOrder(object $order): object
    {
        $tr_id = Transaction::latest()->pluck('id')->first();
        $tr_id = $tr_id + 1;
        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . $this->requestToken
        ];
        $data = json_encode([
            "intent" => "CAPTURE",
            "items" => [
                [
                    "amount" => $order->total,
                ]
            ],
            "locale" => 'ka',
            "shop_order_id" => $order->id,
            "redirect_url" => 'https://onpay.cloud/callback/' . $tr_id,
            "show_shop_order_id_on_extract" => true,
            "capture_method" => "AUTOMATIC",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "GEL",
                        "value" => $order->total,
                    ]
                ]
            ]
        ]);
        $response = $this->sendRequest('checkout/orders', $data, $headers, "POST");
        $result = (array)$response->result;
        if (!isset($result['status']) || $result['status'] !== 'CREATED') {
            throw new Exception("BOG PAYMENT PAY ERROR: Status is not 'Created', it is: {$result['status']}");
        }
        $links = collect($result['links'])->pluck('href', 'rel');
        if (!isset($links['approve'])) {
            return null;
        }

        return (object)[
            'redirect' => $links['approve'],
            'orderid' => $result['order_id']
        ];
    }

    public function refund(object $args): void
    {
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: ' . $this->requestToken
        ];

        $data = http_build_query([
            'order_id' => $args->payId
        ]);

        $result = $this->sendRequest('checkout/refund', $data, $headers, "POST");

        if ($result->httpcode !== 200) {
            throw new Exception("BOG PAYMENT REFUND ERROR: Httpcode is not 200, it is: {$result->httpcode}");
        }
    }

    public function get(object $args): object
    {
        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . $this->requestToken
        ];

        $data = json_encode([]);
        $result = $this->sendRequest("checkout/payment/$args->order_id", $data, $headers, "GET");

        if ($result->httpcode !== 200) {
            throw new Exception("BOG PAYMENT GET ERROR: Httpcode is not 200, it is {$result->httpcode}");
        }
        return $result;
    }

    public function transactionStatus(object $argument): object
    {
        if (!$argument) {
            throw new Exception("BOG PAYMENT getOrderStatus ERROR: Order not set!");
        }

        if (!$argument->payId) {
            throw new Exception("BOG PAYMENT getOrderStatus ERROR: payment_hash not set!");
        }

        $details = $this->get((object) [
            'order_id' => $argument->payId
        ]);
        $response = $details->result;

        return (object)[
            'status' => $response->status,
        ];
    }
}
