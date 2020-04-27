<?php

require_once __DIR__ . "/_helper/_init.php";

$response = [
    "status"                   => RecommenderResponse::STATUS_SUCCESS,
    "response_type"            => RecommenderResponse::RESPONSE_TYPE_NEXT_QUESTION,
    "recomander_id"            => $random_recomander_id,
    "message"                  => "Start",
    "message_type"             => RecommenderResponse::MESSAGE_TYPE_INFO,
    "answer_response"          => "",
    "answer_response_type"     => RecommenderResponse::MESSAGE_TYPE_INFO,
    "progress"                 => 0,
    "progress_type"            => ilProgressBar::TYPE_INFO,
    "learning_progress_status" => RecommenderResponse::LEARNING_PROGRESS_STATUS_NOT_ATTEMPTED,
    "competences"              => $random_competences
];

require_once __DIR__ . "/_helper/_output.php";
