<?php

function generateAccessToken() {
    return bin2hex(random_bytes(16));
}

$valid_tokens = [];

function addValidToken($token) {
    global $valid_tokens;
    $valid_tokens[$token] = true;
}

function isValidToken($token) {
    global $valid_tokens;
    return isset($valid_tokens[$token]);
}

// Gerando um token padrão para fins de teste
addValidToken('f1c9fbb0e8da460ea8acbc7166bab214');