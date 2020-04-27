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
    "progress_display"          => 1, //0 | 1
    "progress"                 => 0.5,
    "progress_type"            => ilProgressBar::TYPE_INFO, // 1. ilProgressBar::TYPE_INFO | 2. ilProgressBar::TYPE_SUCCESS |  3. ilProgressBar::TYPE_WARNING | 4. ilProgressBar::TYPE_DANGER
    "learning_progress_status" => RecommenderResponse::LEARNING_PROGRESS_STATUS_IN_PROGRESS, // 1. ilLPStatus::LP_STATUS_IN_PROGRESS_NUM | 2. ilLPStatus::LP_STATUS_COMPLETED_NUM | 3. LEARNING_PROGRESS_STATUS_FAILED
    "competences"              => $random_competences,
    "progress_meters"           => $random_progress_meters
];

require_once __DIR__ . "/_helper/_output.php";
