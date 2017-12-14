<?php
/**
 * Class LearningProgressStatusRepresentation
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class LearningProgressStatusRepresentation {
	// User has called the training object.
/*	const ilLPStatus_NOT_ATTEMPTED = 1;
	// User has called a question from the training object.
	const PROGR_STATUS_IN_PROGRESS = 2;
	// User has achieved the target level
	const PROGR_STATUS_EDITED = 3;

	static $ARR_PROGR_STATUS = array(
		ilLPStatus::ilLPStatus_NOT_ATTEMPTED
		, self::PROGR_STATUS_IN_PROGRESS
		, self::PROGR_STATUS_EDITED
	);*/

	protected static $dropdown_cache;


	protected static $status_array = array(
		ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM => 'status_not_attempted',
		ilLPStatus::LP_STATUS_IN_PROGRESS_NUM => 'status_in_progress',
		ilLPStatus::LP_STATUS_COMPLETED_NUM => 'status_completed'
	);

//TODO für die Filterung ist es notwendig das not attempted nicht 0 ist -> deshalb hier eigene stati nummern und funktion die zurück mapped auf nummerische werte von ilLPStatus beim eigentlichen filtern. Evtl !empty abfrage in table gui bei filtern

	/**
	 * Get a user readable representation of a status.
	 */
	static public function statusToRepr($status) {

		$pl = ilDhbwTrainingPlugin::getInstance();

		if ($status == ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM) {
			return $pl->txt("status_not_attempted");
		}
		if ($status == ilLPStatus::LP_STATUS_IN_PROGRESS_NUM) {
			return $pl->txt("status_in_progress");
		}
		if ($status == ilLPStatus::LP_STATUS_COMPLETED_NUM) {
			return $pl->txt("status_completed");
		}
		return '';
	}


	static public function getStatusImage($status) {

		switch($status)
		{
			case ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM:
				return ilUtil::getImagePath('scorm/not_attempted.svg');
				break;

			case ilLPStatus::LP_STATUS_IN_PROGRESS_NUM:
				return ilUtil::getImagePath('scorm/incomplete.svg');
				break;

			case ilLPStatus::LP_STATUS_COMPLETED_NUM:
				return ilUtil::getImagePath('scorm/complete.svg');
				break;
		}
	}

	public static function getDropdownData() {
		if(self::$dropdown_cache == null) {
			self::$dropdown_cache = self::$status_array;
		}
		$arr_dropdown = self::$dropdown_cache;

		return $arr_dropdown;
	}

	public static function getDropdownDataLocalized($pl_obj) {
		$data = self::getDropdownData();
		foreach($data as $key=>$entry) {
			if($entry != '') {
				$data[$key] = $pl_obj->txt($entry);
			}
		}
		return $data;
	}

}