<?php

require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/Interface/QuestionPool/class.xdhtQuestionPoolFactoryInterface.php');

/**
 * Class xdhtQuestionPoolFactory
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class xdhtQuestionPoolFactory implements xdhtQuestionPoolFactoryInterface {


	public function getQuestionPoolObjectById($id) {
		global $ilDB;

		$sql = "SELECT * FROM object_data AS object
				inner join object_reference AS reference ON object.obj_id = reference.obj_id
				WHERE object.obj_id = $id";

		$set = $ilDB->query($sql);

		$arr_question_pool = array();
		while($row = $ilDB->fetchAssoc($set)) {
			$arr_question_pool[] = $row;
		}

		return $arr_question_pool;
	}

	/**
	 * @inheritDoc
	 */
	public function getQuestionPools() {
		global $ilDB;

		$sql = "SELECT * FROM object_data AS object
				inner join object_reference AS reference ON object.obj_id = reference.obj_id
				WHERE object.type = 'qpl'";

		$set = $ilDB->query($sql);

		$arr_question_pools = array();
		while($row = $ilDB->fetchAssoc($set)) {
			$arr_question_pools[] = $row;
		}

		return $arr_question_pools;
	}


	/**
	 * @inheritDoc
	 */
	public function getQuestionPoolIds() {
		global $ilDB;

		$sql = "SELECT reference.ref_id FROM object_data AS object
				inner join object_reference AS reference ON object.obj_id = reference.obj_id
				WHERE object.type = 'qpl'";

		$set = $ilDB->query($sql);

		$arr_question_pools = array();
		while($row = $ilDB->fetchAssoc($set)) {
			$arr_question_pools[] = $row;
		}

		return $arr_question_pools;
	}

	/**
	 * @inheritDoc
	 */
	public function getSelectOptionsArray() {
		$question_pools_array = $this->getQuestionPools();
		$sel_opt_array = [];
		foreach($question_pools_array as $question_pool) {
			$sel_opt_array[$question_pool['ref_id']] = $question_pool['title'];
		}
		return $sel_opt_array;
	}

}