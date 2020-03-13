<?php

require_once __DIR__ . "/_helper/_init.php";

$response = [
    "status"          => RecommenderResponse::STATUS_SUCCESS,
    "response_type"   => RecommenderResponse::RESPONSE_TYPE_TEST_IS_FINISHED,
    "recomander_id"   => $random_recomander_id,
    "message"         => "Rating",
    "answer_response" => ""
];

require_once __DIR__ . "/_helper/_output.php";
