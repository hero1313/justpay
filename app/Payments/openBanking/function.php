<?php
function request_for_signature($endpoint, $dynamicHeaders, $requestMethod = 'get', $body = "", $debug = false)
{

    global $host;
    $host = "xs2a-sandbox.bog.ge";
    //variables
    $res = [];
    $openSSL = openssl_pkcs12_read(file_get_contents(QSEAL_CERT), $res, QSEAL_PASS);
    if (!$openSSL) {
        die('died');
    }
    
    $client_cert = $res['cert'];
    $client_cert = trim(preg_replace('#-.*-#', '', $client_cert));
    $client_cert = str_replace("\n", "", $client_cert);
    $privateKey = $res['pkey'];

    //echo '<textarea rows = "10" style = "width:800px">' . $client_cert . '</textarea>';

    $dt = new DateTime();
    $dt->setTimeZone(new DateTimeZone('UTC'));
    $dt->setTimestamp(time());
    $sigT = $dt->format('Y-m-d') . 'T' . $dt->format('H:i:s') . 'Z';

    $psu_user_agent = $_SERVER['HTTP_USER_AGENT'];
    $psu_ip = '192.168.8.78';
    $request_id = v4();
    $geo_loc = 'GEO:52.506931,13.144558';
    $content_type = 'application/json';

    $headers = array(
        'b64' => false,
        'x5c' => [$client_cert],
        'crit' => ['sigT', 'sigD', 'b64'],
        'sigT' => $sigT,
        'sigD' => [
            'pars' => ['(request-target)', 'host', 'content-type', 'psu-ip-address', 'psu-geo-location', 'psu-user-agent', 'digest', 'x-request-id'],
            'mId' => 'http://uri.etsi.org/19182/HttpHeaders'
        ],
        'alg' => 'RS256', //alg is required
    );

    if (!$body) {
        $body_digest = $body;
    } else {
        $body_digest = json_encode($body);
    }
    $digest = 'SHA-256=' . base64_encode(hash('sha256', $body_digest, true));
    //echo '<textarea rows = "10" style = "width:800px">' . $body_digest . '</textarea><br>';
    $payload = array(
        '(request-target)' => $requestMethod . ' ' . $endpoint,
        'host' => $host,
        'content-type' => $content_type,
        'psu-ip-address' => $psu_ip,
        'psu-geo-location' => $geo_loc,
        'psu-user-agent' => $psu_user_agent,
        'digest' => $digest,
        'X-Request-ID' => $request_id,
    );

    $payloadComponent = '';
    $index = 0;
    foreach ($payload as $key => $value) {
        $delimiter = ': ';
        $payloadComponent .= $index == 0 ? '' : chr(0x0A);
        $payloadComponent .= strtolower($key) . $delimiter . $value;
        $index++;
    }
    //echo '<textarea rows = "10" style = "width:800px">' . $payloadComponent . '</textarea><br>';
    //exit;
    //variables

    $jws = new \Gamegos\JWS\JWS();
    $signature = $jws->encode($headers, $payloadComponent, $privateKey);

    $staticHeaders = array(
        'Content-Type: ' . $content_type,
        'X-Request-ID: ' . $request_id,
        'PSU-IP-Address: ' . $psu_ip,
        'PSU-GEO-Location: ' . $geo_loc,
        'PSU-User-Agent: ' . $psu_user_agent,
        'Digest: ' . $digest,
        'x-jws-signature: ' . $signature,
        'Host: ' . $host,
    );

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://' . $host . $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => strtoupper($requestMethod),
        CURLOPT_SSLCERT => QWAC_CERT,
        CURLOPT_SSLCERTPASSWD => QWAC_PASS,
        CURLOPT_HTTPHEADER => array_merge($staticHeaders, $dynamicHeaders),
        CURLINFO_HEADER_OUT => true
    ));

    if ($requestMethod == 'post' and $body_digest) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body_digest);
    }

    $response = curl_exec($curl);


    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $headerout = curl_getinfo($curl, CURLINFO_HEADER_OUT);
    curl_close($curl);

    if ($debug == true) {
        echo 'X-Request-ID => ' . $request_id . '<br><br>';
        echo 'headerout => ' . $headerout . '<br><br>';
        echo 'signature => ' . $signature . '<br><br>';
        echo 'response =>' . $response;
        echo '<pre>';
        print_r(json_decode($response, true));
        if ($body) {
            echo '<br>Body => ';
            print_r($body);
        }
        exit;
    }
    $response = json_decode($response, true);

    return $response;
}


function v4()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

        // 32 bits for "time_low"
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

function base64UrlEncode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}
