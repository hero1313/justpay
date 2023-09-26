<?php

namespace App\Http\Controllers;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class OpenBankingController extends Controller
{
    public function index()
    {
        session_start(); ///

        date_default_timezone_set('Asia/Tbilisi');
        require __DIR__ . '/openBanking/config.php';
        require __DIR__ . '/openBanking/function.php';
        
        $redirect_url = 'https://localhost/justpay';
        $client_id = 'PSDGE-NBG-402227078';
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
            if ($accounts) {
                $debtor_acc_id = 'bb0547d2-fa5e-4795-8e1e-071430e61cd9'; //$accounts['accounts'][1]['resourceId'];
                $debtor_acc_iban = 'GE51BG0000000845855800'; //$accounts['accounts'][1]['iban']; //GE51BG0000000845855800
        
        
                //$response = request_for_signature('/0.8/v1/accounts/' . $debtor_acc_id, $dynamicHeaders);
        
                //გადახდის ინიცირება
                $creditor_acc_id = '5a11ac28-d1d4-4fd8-8434-5b03a95d3582';
                $creditor_acc_iban = 'GE17BG0000000499508115';
        
                $body = array(
                    'instructedAmount' => ['currency' => 'GEL', 'amount' => 345],
                    'debtorAccount' => ['currency' => 'GEL', 'iban' => $debtor_acc_iban],
                    //'creditorName' => '',
                    'creditorAccount' => ['iban' => $creditor_acc_iban],
                    'remittanceInformationUnstructured' => 'Ref Number Merchant'
                );
        
                $dynamicHeaders = [
                    'Consent-ID: ' . $_SESSION['consentId'],
                    "Authorization: Bearer " . $_SESSION['access_token'],
                    'TPP-Redirect-URI: ' . $redirect_url,
                    //'TPP-Redirect-Preferred: true',
                ];
                dd($dynamicHeaders);
                $response = request_for_signature('/0.8/v1/payments/domestic', $dynamicHeaders, 'post', $body);
            }
            
            echo '<pre>';
            //print_r($body);
            print_r($response);
            exit;
            //get accounts list
        } else if (isset($_GET['state'])) {
            dd(222);

            //მიღებული კონსენტით ავტორიზაციის გაგრძელება access_token - ის მიღება
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://account-ob-sbx.bog.ge/auth/realms/bog-test/protocol/openid-connect/token',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSLCERT => QWAC_CERT,
                CURLOPT_SSLCERTPASSWD => QWAC_PASS,
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
                header("Location: $redirect_url");
                exit;
            }
            echo '<pre>';
            print_r($response);
            exit;
        } else {

            //კონსენტის აღება
            $body = array(
                'access' => ['balances' => [], 'transactions' => []],
                'recurringIndicator' => true,
                'validUntil' => "2023-05-30",
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

                dd(123);
                $_SESSION['consentId'] = $response['consentId'];
                $authorization_endpoint = 'https://account-ob-sbx.bog.ge/auth/realms/bog-test/protocol/openid-connect/auth/';
                $state = generateRandomString(32);
                $verifier = generateRandomString(44);
                $_SESSION['verifier'] = $verifier;
                $code_challenge = base64UrlEncode(hash('sha256', $verifier, true));
        
                $url = $authorization_endpoint . '?client_id=' . $client_id . '&scope=AIS:' . $response['consentId'] . '&response_type=code&state=' . $state . '&code_challenge_method=S256&code_challenge=' . $code_challenge . '&redirect_uri=' . $redirect_url;
                header("Location: $url");
                exit;
            } else {
                echo '<pre>';
                print_r($response);
            }
            dd(222);

        }
        
    }
}
