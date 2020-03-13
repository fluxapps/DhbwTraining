<?php

require_once __DIR__ . "/_helper/_init.php";

$response = [
    "status"          => RecommenderResponse::STATUS_SUCCESS,
    "response_type"   => RecommenderResponse::RESPONSE_TYPE_NEXT_QUESTION,
    "recomander_id"   => $random_recomander_id,
    "message"         => "Answer",
    "answer_response" => json_encode($post["answer"])
];

require_once __DIR__ . "/_helper/_output.php";
