<?php

require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/Interface/Question/interface.xdhtQuestionFactoryInterface.php');

/**
 * Class xdhtQuestionFactory
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */
class xdhtQuestionFactory implements xdhtQuestionFactoryInterface {


	//TODO Refactor -> class for GAP_Question!
	const CLOZE_TYPE_TEXT = 0;
	const CLOZE_TYPE_SELECT = 1;
	const CLOZE_TYPE_NUMERIC = 2;


	/**
	 * @inheritdoc
	 */
	public function getAllQuestions() {

		global $ilDB;

		$sql = "SELECT * FROM qpl_questions
inner join qpl_qst_type on qpl_qst_type.question_type_id = qpl_questions.question_type_fi";

		$set = $ilDB->query($sql);

		$arr_questions = array();
		while ($row = $ilDB->fetchAssoc($set)) {
			$arr_questions[$row['question_id']] = $row;
		}

		return $arr_questions;
	}


	private function getFormattedArray($array) {
		return "'" . implode("', '", $array) . "'";
	}


	/**
	 * @inheritdoc
	 */
	public function getNotAnsweredQuestionsByIds($question_ids, $question_pool_id) {
		global $ilDB;

		$formatted_array = $this->getFormattedArray($question_ids);

		//$sql = "SELECT * FROM qpl_questions where obj_fi = $question_pool_id and question_id IN ($formatted_array)";

		$sql = "SELECT * FROM qpl_questions where question_id IN ($formatted_array)";

		$set = $ilDB->query($sql);

		$arr_questions = array();
		while ($row = $ilDB->fetchAssoc($set)) {
			$arr_questions[] = $row;
		}

		return $arr_questions;
	}


	/**
	 * @inheritdoc
	 */
	public function getQuestionIds($questions) {
		$question_ids = [];
		foreach ($questions as $question) {
			$question_ids[] = $question['question_id'];
		}

		return $question_ids;
	}
}