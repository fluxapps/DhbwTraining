<?php

chdir(__DIR__ . "/../../../../../../../../../../../../..");

require_once __DIR__ . "/../../../../../../../../../../../../../libs/composer/vendor/autoload.php";
require_once __DIR__ . "/../../../../../../vendor/autoload.php";

ilInitialisation::initILIAS();

$ref_id = intval(filter_input(INPUT_GET, 'ref_id'));
if (!$ref_id) {
    die();
}

$facade = xdhtObjectFacade::getInstance($ref_id);

if ($facade->settings()->getRecommenderSystemServer() !== xdhtSettingsInterface::RECOMMENDER_SYSTEM_SERVER_BUILT_IN_DEBUG) {
    die();
}

$all_questions = array_keys(array_filter($facade->xdhtQuestionFactory()->getAllQuestions(), function (array $row) : bool {
    return (!empty($row["description"]) && (strpos($row["description"], "[[") !== false && strpos($row["description"], "]]") !== false));
}));

$random_recomander_id = $all_questions[rand(0, (count($all_questions) - 1))];

try {
    $post = json_decode(file_get_contents("php://input"), true);
} catch (Throwable $ex) {
    $post = null;
}
if (!is_array($post)) {
    $post = [];
}
