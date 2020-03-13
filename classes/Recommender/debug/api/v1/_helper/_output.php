<?php

use srag\DIC\DhbwTraining\DICStatic;

if (!isset($response)) {
    die;
}

$response["debug_server"] = json_encode([
        "GET"      => $_GET,
        "POST"     => $post,
        "sent_response" => $response
    ], JSON_PRETTY_PRINT);

DICStatic::output()->outputJSON($response);
