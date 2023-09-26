<?php

namespace App\Payments;

use App\Models\Order;
use App\Models\Transaction;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Session;

use Carbon\Carbon;

use Exception;

class openBanking
{
    private Object $paymentConfig;
    private String $request_token = '';


    public function getAccoutns()
    {
        date_default_timezone_set('Asia/Tbilisi');
        require __DIR__ . '/openBanking/config.php';
        $redirect_url = 'https://onpay.cloud/open-banking/redirect2';
        $client_id = ;
        $host = 'xs2a-sandbox.bog.ge';
        if (isset($_GET['destroy'])) {
            unset($_SESSION['consentId']);
            unset($_SESSION['access_token']);
        }
        if (isset($_SESSION['access_token']) and isset($_SESSION['consentId'])) {
            //get accounts list
            $dynamicHeaders = [
                'Consent-ID: ' . $_SESSION['consentId'],
                "Authorization: Bearer " . $_SESSION['access_token']
            ];
            $accounts = request_for_signature('/0.8/v1/accounts', $dynamicHeaders);
            // dd($accounts);
            // აქედანაა გასაგრძელებელი

            Session::put('consentId', $_SESSION['consentId']);
            Session::put('access_token', $_SESSION['access_token']);
            
            return $accounts;
            echo '<pre>';
            //print_r($body);
            print_r($response);
            exit;
            //get accounts list
        }
    }

    public function pay($iban){
                require __DIR__ . '/openBanking/config.php';
                require __DIR__ . '/openBanking/function.php';


                // $debtor_acc_id = 'bb0547d2-fa5e-4795-8e1e-071430e61cd9'; //$accounts['accounts'][1]['resourceId'];
                $debtor_acc_iban = $iban; //$accounts['accounts'][1]['iban']; //GE51BG0000000845855800
                $redirect_url = 'https://onpay.cloud/open-banking/redirect';


                //$response = request_for_signature('/0.8/v1/accounts/' . $debtor_acc_id, $dynamicHeaders);

                //გადახდის ინიცირება
                // $creditor_acc_id = '5a11ac28-d1d4-4fd8-8434-5b03a95d3582';
                $creditor_acc_iban = '';

                $body = array(
                    'instructedAmount' => ['currency' => 'GEL', 'amount' => 345],
                    'debtorAccount' => ['currency' => 'GEL', 'iban' => $debtor_acc_iban],
                    //'creditorName' => '',
                    'creditorAccount' => ['iban' => $creditor_acc_iban],
                    'remittanceInformationUnstructured' => 'Ref Number Merchant'
                );

                $dynamicHeaders = [
                    'Consent-ID: ' . Session::get('consentId'),
                    "Authorization: Bearer " . Session::get('access_token'),
                    'TPP-Redirect-URI: ' . $redirect_url,
                    //'TPP-Redirect-Preferred: true',
                ];

                $response = request_for_signature('/0.8/v1/payments/domestic', $dynamicHeaders, 'post', $body);
                return $response;
               }
    public function paymentRequest(object $args)
    {
        session_start(); ///

        date_default_timezone_set('Asia/Tbilisi');
        require __DIR__ . '/openBanking/config.php';
        require __DIR__ . '/openBanking/function.php';

        $redirect_url = 'https://onpay.cloud/open-banking/redirect';
        $client_id = '';
        $host = 'xs2a-sandbox.bog.ge';


        if (isset($_GET['destroy'])) {
            unset($_SESSION['consentId']);
            unset($_SESSION['access_token']);
        }
        //კონსენტის აღება
        $today = Carbon::now()->toDateString();

        $body = array(
            'access' => ['balances' => [], 'transactions' => []],
            'recurringIndicator' => true,
            'validUntil' => $today,
            'frequencyPerDay' => 4,
            'combinedServiceIndicator' => true
        );

        $dynamicHeaders = [
            'TPP-Redirect-URI: ' . $redirect_url,
            'TPP-Redirect-Preferred: true',
        ];


        $response = request_for_signature('/0.8/v1/consents', $dynamicHeaders, 'post', $body);
        //მიღებული კონსენტით ავტორიზაცია
        if ($response and $response['consentId']) {
            $_SESSION['consentId'] = $response['consentId'];
            $authorization_endpoint = 'https://account-ob-sbx.bog.ge/auth/realms/bog-test/protocol/openid-connect/auth/';
            $state = generateRandomString(32);
            $verifier = generateRandomString(44);
            $_SESSION['verifier'] = $verifier;
            $code_challenge = base64UrlEncode(hash('sha256', $verifier, true));

            $url = $authorization_endpoint . '?client_id=' . $client_id . '&scope=AIS:' . $response['consentId'] . '&response_type=code&state=' . $state . '&code_challenge_method=S256&code_challenge=' . $code_challenge . '&redirect_uri=' . $redirect_url;

            return [
                'location' => $url,
                'consentId' => $response['consentId'],
            ];
        } else {
            echo '<pre>';
            print_r($response);
        }
    }




    public function accessToken()
    {
        session_start(); ///


        date_default_timezone_set('Asia/Tbilisi');
        require __DIR__ . '/openBanking/config.php';
        require __DIR__ . '/openBanking/function.php';
        $redirect_url = 'https://onpay.cloud/open-banking/redirect';
        $client_id = '';
        $host = 'xs2a-sandbox.bog.ge';
        if (isset($_GET['destroy'])) {
            unset($_SESSION['consentId']);
            unset($_SESSION['access_token']);
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://account-ob-sbx.bog.ge/auth/realms/bog-test/protocol/openid-connect/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSLCERT => QWAC_CERT,
            CURLOPT_SSLCERTPASSWD => QWAC_PASS,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query(array('code' => $_GET['code'], 'client_id' => $client_id, 'code_verifier' => $_SESSION['verifier'], 'redirect_uri' => $redirect_url, 'grant_type' => 'authorization_code')),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        if ($response['access_token']) {
            $_SESSION['access_token'] = $response['access_token'];
        }
        // $this->getAccoutns($_SESSION['access_token'], $_SESSION['access_token']);
        return $response;


    }
}
