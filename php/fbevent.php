<?php
include("get-location.php");

$userIP = $_SERVER['REMOTE_ADDR'];
$geoData = getGeoLocation($userIP);

echo $userIP;

 $pixelId = '1249024706864038';
 $accessToken = 'EAARhCoDM9UYBPH6HpdFrOFsKAQFfRLbEYkbAZAT2XTLsBbkUrjr9rNFYoq8G3zidl381VLa8zEZC36ZCk5BjZCLYTdH8We0RQswWHukze19jbkTLJNf6DSkRlLGYDBVbRoEX9uCfydjcS8fsFNTGNIpJUYa5ji9OiHNr0tS6hMZA5i9lCYw5ZAGPCcum6jbLkd3QZDZD';
 $url = "https://graph.facebook.com/v11.0/$pixelId/events";

 
 $state = isset($_POST['estado']) ? trim($_POST['estado']) : $geoData['region'] ?? '';
 $city = isset($_POST['cidade']) ? trim($_POST['cidade']) : $geoData['city'] ?? '';

 $telefoneHash = hash('sha256', $telefone);
 $nomeHash = hash("sha256", $nome);
 $estadoHash = hash('sha256', $state);
 $cidadeHash = hash('sha256', $city);
 $paisHash = hash('sha256', $geoData['country']);
 $zipHash = hash("sha256", $geoData['zip']);



$eventData = json_decode($json, true);


$data = [
    'data' => [
        [
            'event_name' => $eventData["eventName"],
            'event_time' => time(),
            'action_source' => 'website',
            'event_source_url' => 'https://lp.flowch.com',
            'user_data' => [
                'external_id' => $_SERVER['REMOTE_ADDR'],
                'client_ip_address' => $_SERVER['REMOTE_ADDR'],
                'client_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'fbc' => filter_input(INPUT_COOKIE, '_fbc') ? filter_input(INPUT_COOKIE, '_fbc') : null,
                'fbp' => filter_input(INPUT_COOKIE, '_fbp'),
                'st' => $estadoHash,
                'country' => $paisHash,
                'ct' => $cidadeHash, 
                'zp' => $zipHash,
            ],
        ],
    ],
    'access_token' => $accessToken,
];


$jsonData = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if ($response === false) {
        echo "Erro ao enviar o evento: " . curl_error($ch);
    } else {
        echo "Evento enviado com sucesso: " . $response;
    }

    curl_close($ch);

?>
