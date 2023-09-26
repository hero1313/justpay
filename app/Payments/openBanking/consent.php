<?php
session_start();
require_once 'vendor/autoload.php';
date_default_timezone_set('Asia/Tbilisi');
require __DIR__ . '/config.php';
require __DIR__ . '/function.php';

$redirect_url = 'https://onpay.cloud/openbanking-callback-consent';
$client_id = 'PSDGE-NBG-402227078';
$host = 'xs2a-sandbox.bog.ge';

if (isset($_SESSION['access_token'])) {
    $body = array(
        'access' => ['balances' => [], 'transactions' => []],
        'recurringIndicator' => true,
        'validUntil' => "2023-04-21",
        'frequencyPerDay' => 4,
        'combinedServiceIndicator' => true
    );

    $response = init_request_for_signature($body, 'consent');
    echo '<pre>';
    print_r($response);
    exit;

    if ($response['consentId']) {

        echo ',,,,,,,,,mmm';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://' . $host . '/0.8/v1/accounts',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSLCERT => QWAC_CERT,
            CURLOPT_SSLCERTPASSWD => QWAC_PASS,
            //CURLOPT_POSTFIELDS => $body_digest,
            CURLOPT_HTTPHEADER => array(
                //'Host: ' . $host,
                'Content-Type: ' . $content_type,
                'X-Request-ID: ' . v4(),
                'Consent-ID: ' . $response['consentId'],
            ),
        ));

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        echo $httpcode;
        //curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        //$headerout = curl_getinfo($curl, CURLINFO_HEADER_OUT);
        echo '<br>' . $headerout . '<br>';
        curl_close($curl);

        $response = json_decode($response, true);
        echo '<pre>';
        print_r($response);
        exit;
    }
} else if (isset($_GET['state'])) {
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
    $authorization_endpoint = 'https://account-ob-sbx.bog.ge/auth/realms/bog-test/protocol/openid-connect/auth/';
    $state = generateRandomString(32);
    $verifier = generateRandomString(44);
    $_SESSION['verifier'] = $verifier;
    $code_challenge = base64UrlEncode(hash('sha256', $verifier, true));

    // $url = $authorization_endpoint . '?client_id=' . $client_id . '&scope=pis:' . $response['paymentId'] . '&response_type=code&state=' . $state . '&code_challenge_method=S256&code_challenge=' . $code_challenge . '&redirect_uri=' . $redirect_url;

    $url = $authorization_endpoint . '?client_id=' . $client_id . '&response_type=code&state=' . $state . '&code_challenge_method=S256&code_challenge=' . $code_challenge . '&redirect_uri=' . $redirect_url;
    header("Location: $url");
    exit;
}
