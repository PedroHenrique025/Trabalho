<?php
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $url = 'http://localhost/api/getcategoryid?id='.$id;

    $response = file_get_contents($url);
    
    if ($response === false) {
        die('Erro ao acessar a API');
    }
    
    $data = json_decode($response);
    
    if ($data === null) {
        die('Erro ao decodificar JSON');
    }
    
    echo $response;
?>