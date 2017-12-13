<?php
/**
 * Class LearningProgressStatusRepresentation
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class LearningProgressStatusRepresentation {
	// User has called the training object.
	const PROGR_STATUS_NOT_ATTEMPTED = 1;
	// User has called a question from the training object.
	const PROGR_STATUS_IN_PROGRESS = 2;
	// User has achieved the target level
	const PROGR_STATUS_EDITED = 3;

	static $ARR_PROGR_STATUS = array(
		self::PROGR_STATUS_NOT_ATTEMPTED
		, self::PROGR_STATUS_IN_PROGRESS
		, self::PROGR_STATUS_EDITED
	);



	/**
	 * Get a user readable representation of a status.
	 */
	static public function statusToRepr($a_status) {

		$pl = ilDhbwTrainingPlugin::getInstance();

		if ($a_status == self::PROGR_STATUS_NOT_ATTEMPTED) {
			return $pl->txt("prgr_status_attempted");
		}
		if ($a_status == self::PROGR_STATUS_IN_PROGRESS) {
			return $pl->txt("prgr_status_in_progress");
		}
		if ($a_status == self::PROGR_STATUS_EDITED) {
			return $pl->txt("prgr_status_edited");
		}
		return '';
	}


	static public function getStatusImage($participant_progress_status) {

		if($participant_progress_status = self::PROGR_STATUS_NOT_ATTEMPTED) {

		}

		if(in_array($participant_progress_status,array(self::PROGR_STATUS_NOT_ATTEMPTED,self::PROGR_STATUS_IN_PROGRESS,self::PROGR_STATUS_EDITED))) {
			return '<img src="./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/TrainingProgram/templates/images/prog_status_not_valid_soon.svg">';
		} else {
			return '<img src="./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/TrainingProgram/templates/images/prg_status_'.$participant_progress_status.'.svg">';
		}
	}

	public static function getDropdownData($add_status_new = false, $add_empty = false) {
		if(self::$dropdown_cache == null) {
			self::$dropdown_cache = self::getArray('id', 'title');
		}
		$arr_dropdown = self::$dropdown_cache;

		if(!$add_status_new) {
			unset($arr_dropdown[self::SKILL_STATUS_ID_NEW]);
		}

		if($add_empty) {
			$arr_dropdown  = array('' => '') + $arr_dropdown;
		}

		return $arr_dropdown;
	}

	public static function getDropdownDataLocalized($lang_obj, $add_status_new = false, $add_empty = false, $prefix = "") {
		$data = self::getDropdownData($add_status_new, $add_empty);
		foreach($data as $key=>$entry) {
			if($entry != '') {
				$data[$key] = $lang_obj->txt($prefix.$entry);
			}
		}
		return $data;
	}


}