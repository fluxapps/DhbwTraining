<?php
/**
 * Class ilObjDhbwTrainingListGUI
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

require_once('./Services/Repository/classes/class.ilObjectPluginListGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/class.ilObjDhbwTrainingGUI.php');

class ilObjDhbwTrainingListGUI extends ilObjectPluginListGUI {

	function getGuiClass() {
		return ilObjDhbwTrainingGUI::class;
	}


	function initCommands() {
		$this->timings_enabled = false;
		$this->subscribe_enabled = false;
		$this->payment_enabled = false;
		$this->link_enabled = false;
		$this->info_screen_enabled = true;
		$this->delete_enabled = true;

		// Should be overwritten according to status
		$this->cut_enabled = false;
		$this->copy_enabled = false;

		return array(
			array(
				'permission' => 'read',
				'cmd' => ilObjDhbwTrainingGUI::CMD_STANDARD,
				'default' => 'true'
			)
		);
	}


	function initType() {
		$this->setType(ilDhbwTrainingPlugin::PLUGIN_PREFIX);
	}
}