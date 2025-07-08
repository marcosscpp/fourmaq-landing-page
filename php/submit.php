<?php

function sendJsonResponse($status, $message) {
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}

function formatPhoneNumber($phone) {
    $phone = preg_replace('/\D/', '', $phone);
    
    if (strlen($phone) < 3) {
        return null;
    }

    $ddi = "55";
    $ddd = substr($phone, 0, 2);
    $numeroSemDDD = substr($phone, 2);

    return "+{$ddi} {$ddd} {$numeroSemDDD}";
}

function sendLeadRequest($data, $headers) {
    $url = "https://tiamofourmaqcombr.kommo.com/api/v4/leads/complex";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';
    $state = isset($_POST['estado']) ? trim($_POST['estado']) : '';
    $city = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
    $preference = isset($_POST['preferencia']) ? trim($_POST['preferencia']) : '';
    $urgency = isset($_POST['urgencia']) ? trim($_POST['urgencia']) : '';

    // UTM Parameters
    $utm_source = isset($_POST['utm_source']) ? htmlspecialchars($_POST['utm_source']) : 'Desconhecido';
    $utm_medium = isset($_POST['utm_medium']) ? htmlspecialchars($_POST['utm_medium']) : 'Desconhecido';
    $utm_campaign = isset($_POST['utm_campaign']) ? htmlspecialchars($_POST['utm_campaign']) : 'Desconhecido';
    $utm_content = isset($_POST['utm_content']) ? htmlspecialchars($_POST['utm_content']) : 'Desconhecido';

    if (empty($name) || empty($email) || empty($phone)) {
        sendJsonResponse('error', 'Todos os campos são obrigatórios.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendJsonResponse('error', 'Email inválido.');
    }

    $formattedPhone = formatPhoneNumber($phone);
    if (!$formattedPhone) {
        sendJsonResponse('error', 'Número de telefone inválido.');
    }

    $headers = [
        "accept: application/json",
        "content-type: application/json",
        "authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjQxMDAzYzhjNDQwZWQ5YjZlZjExMWNlZGVmYzQ0ZGQ2ZTc4ZmJjZjcwMzFmMDVjY2VmYjA4YmE5NjA4OTRhYmQzNTcxZTk3Y2VkNzE4OWRiIn0.eyJhdWQiOiJlYTk2YmYwYi0xMGZmLTQzODItYjkwZS1iYjJiZjQ0ODI4OTIiLCJqdGkiOiI0MTAwM2M4YzQ0MGVkOWI2ZWYxMTFjZWRlZmM0NGRkNmU3OGZiY2Y3MDMxZjA1Y2NlZmIwOGJhOTYwODk0YWJkMzU3MWU5N2NlZDcxODlkYiIsImlhdCI6MTc1MTY2MDEzOCwibmJmIjoxNzUxNjYwMTM4LCJleHAiOjE5MDkzNTM2MDAsInN1YiI6IjEzNDY5ODAzIiwiZ3JhbnRfdHlwZSI6IiIsImFjY291bnRfaWQiOjM0ODM3MDM5LCJiYXNlX2RvbWFpbiI6ImtvbW1vLmNvbSIsInZlcnNpb24iOjIsInNjb3BlcyI6WyJjcm0iLCJmaWxlcyIsImZpbGVzX2RlbGV0ZSIsIm5vdGlmaWNhdGlvbnMiLCJwdXNoX25vdGlmaWNhdGlvbnMiXSwiaGFzaF91dWlkIjoiZDBjZDQ1YzctMjIyYS00ZjY5LWI5YjEtZGI4NjgwMjc4ZDkwIiwiYXBpX2RvbWFpbiI6ImFwaS1jLmtvbW1vLmNvbSJ9.aF413iMvEDRN9nSz40L4rDnHQrbI7Wqa-Cs_PwvocG5nXSpDYzLvevDs7r_fhL2f44DzM_yXR_0SEaIHePspgi4fjZB8LJCV8_kKBe-Es3kVKbjV9ymrLVG5o-725zFc4QxT_yBNyffum9JhTX4lVXR0GQZvKoHxfE3ZattAxfQq5calTjj42HKuEQFNO5oD_45MOMZnaDpWIhGT6GAu6N35W8v-cDXVqJAjMT9eN2H-Diwz_LgVVH_u5C14WqxP-5K41_w5wLNLe4PWpoRVPEpJs78-8GHjPKPP7uZy-GafZZ9SMz545NbBuVmB4ywlxKLBiBphmKhqD2cQNaVbFA"
    ];

    $nameParts = explode(' ', $name);
    $lastName = array_pop($nameParts);
    $firstName = implode(' ', $nameParts);

    $data = [
        [
            "_embedded" => [
                "contacts" => [
                    [
                        "name" => $name,
                        "first_name" => $firstName,
                        "last_name" => $lastName,
                        "custom_fields_values" => [
                            [
                                "field_id" => 976990,
                                "values" => [["value" => $email]]
                            ],
                            [
                                "field_id" => 976988,
                                "values" => [["value" => $formattedPhone]]
                            ],
                            [
                                "field_id" => 978832,
                                "values" => [["value" => $state]]
                            ],
                            [
                                "field_id" => 978828,
                                "values" => [["value" => $city]]
                            ],
                            [
                                "field_id" => 978836,
                                "values" => [["value" => $preference]]
                            ],
                            [
                                "field_id" => 978838,
                                "values" => [["value" => $urgency]]
                            ],
                            [
                                "field_id" => 978840,
                                "values" => [["value" => $utm_content]]
                            ],
                            [
                                "field_id" => 978842,
                                "values" => [["value" => $utm_campaign]]
                            ],
                            [
                                "field_id" => 978844,
                                "values" => [["value" => $utm_medium]]
                            ],
                            [
                                "field_id" => 978846,
                                "values" => [["value" => $utm_source]]
                            ]
                        ]
                    ]
                ],
            ],
            "pipeline_id" => 11508851,
            "name" => $name
        ]
    ];

    $response = json_decode(sendLeadRequest($data, $headers));

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'message' => 'Dados enviados com sucesso!',
        'response' => $response
    ]);

} else {
    sendJsonResponse('error', 'Método inválido. Use o método POST.');
}