<?php
include("get-location.php");

$userIP = $_SERVER['REMOTE_ADDR'];
$geoData = getGeoLocation($userIP);

$json = file_get_contents('php://input');
$eventData = json_decode($json, true);

if (!$eventData || !isset($eventData['eventName'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing event data']);
    exit;
}

$pixelId = '647458264381624';
$accessToken = 'EAARhCoDM9UYBPLYIrtUVhUikSlJChlcIc2TX3DJMlMusFhf5BWfv4NznO8eH3k6pU6W9WjdMCmovpb4CZAiYrs2QZAEowJBJltdHGXAoXNZB4aSOZAjPofckGGVpigA4wdnbSyRgLUWzizxoWxw4gHxfdTgKGfgO6DDhYZArifUg4R9Q6wZAt0u6iAfoYVlwZDZD';
$url = "https://graph.facebook.com/v11.0/$pixelId/events";

$telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
$nome = isset($_POST['nome']) ? $_POST['nome'] : '';

$telefoneHash = !empty($telefone) ? hash('sha256', $telefone) : null;
$nomeHash = !empty($nome) ? hash("sha256", $nome) : null;
$estadoHash = hash('sha256', $geoData['region']);
$cidadeHash = hash('sha256', $geoData['city']);
$paisHash = hash('sha256', $geoData['country']);
$zipHash = hash("sha256", $geoData['zip']);

$data = [
    'data' => [
        [
            'event_name' => $eventData["eventName"],
            'event_time' => time(),
            'action_source' => 'website',
            'event_source_url' => 'https://lp.fourmaq.com.br/maquinas-agricolas',
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
