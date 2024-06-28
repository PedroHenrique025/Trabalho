<?php

$token = 'f1c9fbb0e8da460ea8acbc7166bab214';

$data = [
    'id' => '8',
    'cod' => '321',
    'title' => 'Livro 321',
    'synopsis' => '321Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    'id_category' => '1'
];

$jsonData = json_encode($data);

$url = 'http://localhost/api/updatebook';

$curl = curl_init($url);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData),
    'Authorization: ' . $token
]);

$response = curl_exec($curl);

if ($response === false) {
    $error = curl_error($curl);
    echo "Error in cURL request: " . $error;
} else {
    echo "Return: " . $response;
}

curl_close($curl);