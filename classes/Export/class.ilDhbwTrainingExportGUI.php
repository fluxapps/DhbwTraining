<?php
/**
 * Class ilDhbwTrainingExportGUI
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class ilDhbwTrainingExportGUI extends ilExportGUI {

	public function __construct($a_parent_gui, $a_main_obj = null)
	{
		global $ilPluginAdmin;

		parent::__construct($a_parent_gui, $a_main_obj);

		$this->addFormat('xml', $a_parent_gui->lng->txt('ass_create_export_file'));
		$pl_names = $ilPluginAdmin->getActivePluginsForSlot(IL_COMP_PLUGIN, 'Test', 'texp');
		foreach($pl_names as $pl)
		{
			/**
			 * @var $plugin ilTestExportPlugin
			 */
			$plugin = ilPluginAdmin::getPluginObject(IL_COMP_PLUGIN, 'DhbwTraining', 'xdht', $pl);
			$plugin->setTest($this->obj);
			$this->addFormat(
				$plugin->getFormat(),
				$plugin->getFormatLabel(),
				$plugin,
				'export'
			);
		}
	}
}