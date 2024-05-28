<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['city']) || empty($data['city'])) {
    echo json_encode(['cod' => 400, 'message' => 'Você precisa digitar uma cidade...']);
    exit;
}

$cityName = urlencode($data['city']);
$apiKey = '8a60b2de14f7a17c7a11706b2cfcd87c';
$apiUrl = "https://api.openweathermap.org/data/2.5/weather?q={$cityName}&appid={$apiKey}&units=metric&lang=pt_br";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    echo json_encode(['cod' => 500, 'message' => 'Erro ao se conectar à API']);
    exit;
}

$json = json_decode($response, true);

if ($json['cod'] === 200) {
    echo json_encode([
        'cod' => 200,
        'city' => $json['name'],
        'country' => $json['sys']['country'],
        'temp' => $json['main']['temp'],
        'tempMax' => $json['main']['temp_max'],
        'tempMin' => $json['main']['temp_min'],
        'description' => $json['weather'][0]['description'],
        'tempIcon' => $json['weather'][0]['icon'],
        'windSpeed' => $json['wind']['speed'],
        'humidity' => $json['main']['humidity']
    ]);
} else {
    echo json_encode([
        'cod' => $json['cod'],
        'message' => $json['message']
    ]);
}
?>
