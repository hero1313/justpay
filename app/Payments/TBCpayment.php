<?php

namespace App\Payments;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Payments\Payment;
use Exception;

class TBCpayment extends Payment
{
    private Object $paymentConfig;
    private String $request_token;

	private function tbcRequest(string $endPoint, string $data, array $headers, string $method ) : object
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->paymentConfig->api_endpoint . $endPoint,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers
        ]);

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        $decoded = json_decode($response) ?? $response;
        return (object) [
            'httpcode' => $httpcode,
            'response' => $decoded,
        ];
	}

    protected function prepare() : void {
        $this->paymentConfig = (object) config('paymentConfig.tbc');
        $data = rawurldecode(
            http_build_query([
                'client_id' => $this->config->client_id,
                'client_secret' => $this->config->client_key
            ])
        );
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'apiKey: ' . $this->paymentConfig->api_key
        ];
        $result = $this->tbcRequest('access-token', $data, $headers, "POST");
        $response = $result->response ?? null;
        if(is_object($response) && $response->token_type && $response->access_token) {
            $this->request_token = "{$response->token_type} {$response->access_token}";
        }

    }

    public function createOrder(object $order) : object{
        $tr_id = Transaction::latest()->pluck('id')->first();
        $tr_id = $tr_id + 1;
        // $redirect_url = $this->paymentConfig->redirect_url;
        // $callback_url = $this->paymentConfig->callback_url;
        $data = json_encode([
            'amount' => [
                'currency' => 'GEL',
                'total' => $order->total
            ],
            'returnurl' => 'https://onpay.cloud/callback/'. $tr_id,
            // 'redirectUrl' => 'https://onpay.cloud/redirect/'. $tr_id,
            'callbackUrl' => 'https://onpay.cloud/api/payment-callback/'. $order->user_id,
            'preAuth' => false,
            'language' => 'EN',
            'merchantPaymentId' => $order->id
        ]);
        $headers = [
            'Content-Type: application/json',
            'ApiKey: ' . $this->paymentConfig->api_key,
            'Authorization: ' . $this->request_token
        ];
        $result = $this->tbcRequest('payments', $data, $headers, "POST");

        if($result->httpcode !== 200) {
            throw new Exception("TBC PAYMENT PAY ERROR: HttpCode is not 200, it is: {$result->httpcode}");
        }

        $response = $result->response;

        if($response->status !== 'Created') {
            throw new Exception("TBC PAYMENT PAY ERROR: Status is not 'Created', it is: {$response->status}");
        }

        $links = collect($response->links)->pluck('uri', 'rel');

        
        if(!isset($links['approval_url'])) {
            throw new Exception("TBC PAYMENT PAY ERROR: 'approval_url' not found in links");
        }
        
        return (object) [
            'redirect' => $links['approval_url'],
            'payId' => $response->payId,
            'currency' => $response->currency,
            'total' => $response->amount,
            'order_id' => $response->merchantPaymentId,
            'status' => $response->status,
        ];
    }

    // თანხის დაბრუნება
    public function refund(object $argument) : void{
        $data = json_encode([
             'amount' => $argument->total
        ]);

        $headers = [
            'Content-Type: application/json',
            'ApiKey: ' . $this->paymentConfig->api_key,
            'Authorization: ' . $this->request_token
        ];

        $result = $this->tbcRequest("payments/{$argument->payId}/cancel", $data, $headers, "POST");
        // dd($result);

        if($result->httpcode !== 200) {
            $details = json_encode($result->response);
            throw new Exception("TBC PAYMENT REFUND ERROR: Httpcode is not 200, it is: {$result->httpcode}, Details: {$details}, Data : {$data}");
        }

    }

// სტატუსის შემმოწმებელი
    public function transactionStatus(object $argument) : object{
        $headers = [
            'Content-Type: application/json',
            'ApiKey: ' . $this->paymentConfig->api_key,
            'Authorization: ' . $this->request_token
        ];
        $result = $this->tbcRequest("payments/{$argument->payId}", '', $headers, "GET");

        if($result->httpcode !== 200) {
            throw new Exception("TBC PAYMENT GET ERROR: Httpcode is not 200, it is {$result->httpcode}");
        }
        return $result->response;
    }
}
