<?php

$url = 'http://localhost/listbooks';

$response = file_get_contents($url);

if ($response === false) {
    die('Erro ao acessar a API');
}

$data = json_decode($response);

if ($data === null) {
    die('Erro ao decodificar JSON');
}

var_dump($data);