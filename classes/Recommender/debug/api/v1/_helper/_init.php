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

$random_competences = $facade->settings()->getRecommenderSystemServerBuiltInDebugCompetences();
if (!empty($random_competences)) {
    $random_competences = array_map(function (array $skill_ids) : int {
        return $skill_ids[rand(0, (count($skill_ids) - 1))];
    }, array_reduce($random_competences, function (array $random_competences, array $competence) : array {
        if (!isset($random_competences[$competence["competence_id"]])) {
            $random_competences[$competence["competence_id"]] = [];
        }
        $random_competences[$competence["competence_id"]][] = $competence["skill_id"];

        return $random_competences;
    }, []));
} else {
    $random_competences = null;
}

try {
    $post = json_decode(file_get_contents("php://input"), true);
} catch (Throwable $ex) {
    $post = null;
}
if (!is_array($post)) {
    $post = [];
}
