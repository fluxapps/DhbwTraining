<?php
/**
 * Class ilObjDhbwTrainingListGUI
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

require_once('./Services/Repository/classes/class.ilObjectPluginListGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/class.ilObjDhbwTrainingGUI.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/Traits/trait.xdhtDIC.php');

class ilObjDhbwTrainingListGUI extends ilObjectPluginListGUI {

	use xdhtDIC;

	function getGuiClass() {
		return ilObjDhbwTrainingGUI::class;
	}


	function initCommands() {
		$this->timings_enabled = false;
		$this->subscribe_enabled = false;
		$this->payment_enabled = false;
		$this->link_enabled = true;
		$this->info_screen_enabled = true;
		$this->delete_enabled = true;

		// Should be overwritten according to status
		$this->cut_enabled = true;
		$this->copy_enabled = true;

		return array(
			array(
				'permission' => 'read',
				'cmd' => ilObjDhbwTrainingGUI::CMD_STANDARD,
				'default' => 'true',
			),
			array(
				'permission' => 'read',
				'cmd' => ilObjDhbwTrainingGUI::CMD_EDIT,
				'lang_var' => $this->txt('settings'),
			),
			array(
				'permission' => 'read',
				'cmd' => xdhtParticipantGUI::CMD_STANDARD,
				'lang_var' => $this->txt('participant'),
			)
		);
	}

	/**
	 * Get item properties
	 *
	 * @return	array		array of property arrays:
	 *						"alert" (boolean) => display as an alert property (usually in red)
	 *						"property" (string) => property name
	 *						"value" (string) => property value
	 */
	function getProperties()
	{
		global $lng;

		$props = array();

		include_once "./Customizing/global/plugins/Services/Repository/RepositoryObject/DhbwTraining/classes/class.ilObjDhbwTraining.php";
		if (!ilObjDhbwTraining::_lookupOnline($this->obj_id))
		{
			$props[] = array("alert" => true, "property" => $lng->txt("status"),
				"value" => $lng->txt("offline"));
		}
		return $props;
	}


	function initType() {
		$this->setType(ilDhbwTrainingPlugin::PLUGIN_PREFIX);
	}
}