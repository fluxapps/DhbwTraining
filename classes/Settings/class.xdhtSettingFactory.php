<?php

require_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/Settings/class.xdhtSettings.php");
require_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/Interface/Settings/interface.xdhtSettingFactoryInterface.php");

/**
 * Class xdhtSettingFactory
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */
class xdhtSettingFactory implements xdhtSettingFactoryInterface {

	/**
	 * @inheritDoc
	 */
	public function findById($id) {
		return xdhtSettings::where(array( 'id' => $id ))->first();
	}


	/**
	 * @inheritDoc
	 */
	public function findOrGetInstanceByObjId($dhbw_training_object_id) {
		/**
		 * @inheritdoc
		 */
		$obj = xdhtSettings::where(array( 'dhbw_training_object_id' => $dhbw_training_object_id ))->first();

		if (is_object($obj)) {
			return $obj;
		} else {
			$obj = new xdhtSettings();
			$obj->setDhbwTrainingObjectId($dhbw_training_object_id);

			return $obj;
		}
	}

}