<?php

use srag\DIC\DhbwTraining\DICStatic;

if (!isset($response)) {
    die;
}

$response["debug_server"] = [
    "GET"           => $_GET,
    "POST"          => $post,
    "sent_response" => $response
];

DICStatic::output()->outputJSON($response);
