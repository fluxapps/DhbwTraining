<?php

require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/Interface/Question/interface.xdhtQuestionFactoryInterface.php');

/**
 * Class xdhtQuestionFactory
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class xdhtQuestionFactory implements xdhtQuestionFactoryInterface {

	public function getAllQuestionsByQuestionPoolId($question_pool_id) {
		global $ilDB;

		$sql = "SELECT * FROM ilias.qpl_questions where obj_fi = $question_pool_id";

		$set = $ilDB->query($sql);

		$arr_questions = array();
		while($row = $ilDB->fetchAssoc($set)) {
			$arr_questions[] = $row;
		}

		return $arr_questions;
	}
}