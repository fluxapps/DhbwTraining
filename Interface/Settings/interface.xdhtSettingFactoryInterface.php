<?php

require_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Settings/class.xdhtSettings.php");

/**
 * Class xdhtSettingFactory
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

interface xdhtSettingFactoryInterface {

	const QUESTION_TYPE = 'qpl';

	/**
	 * @param int    $id
	 *
	 * @description get xdhtSettings by id
	 *
	 * @return \xdhtSettingsInterface
	 */
	public function findById($id);


	/**
	 * @param int $dhbw_training_object_id
	 *
	 * @description Returns an existing Object with given object_id
	 * or a new Instance of xaseSettingM1 with given object_id set but not yet created
	 *
	 * @return \xdhtSettingsInterface
	 */
	public function findOrGetInstanceByObjId($dhbw_training_object_id);
}