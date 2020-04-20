<?php

require_once __DIR__ . "/_helper/_init.php";

$response = [
    "status"                   => RecommenderResponse::STATUS_SUCCESS,
    "response_type"            => RecommenderResponse::RESPONSE_TYPE_NEXT_QUESTION,
    "recomander_id"            => $random_recomander_id,
    "message"                  => "Answer",
    "message_type"             => RecommenderResponse::MESSAGE_TYPE_INFO,
    "answer_response"          => json_encode($post["answer"]),
    "answer_response_type"     => RecommenderResponse::MESSAGE_TYPE_QUESTION,
    "progress"                 => 0.5,
    "progress_type"            => RecommenderResponse::MESSAGE_TYPE_QUESTION,
    "learning_progress_status" => RecommenderResponse::LEARNING_PROGRESS_STATUS_IN_PROGRESS,
    "competences"              => $random_competences
];

require_once __DIR__ . "/_helper/_output.php";
