<?php

include_once("./Services/Repository/classes/class.ilRepositoryObjectPlugin.php");

/**
 * Class ilDhbwTrainingPlugin
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

class ilDhbwTrainingPlugin extends ilRepositoryObjectPlugin {

	const PLUGIN_PREFIX = 'xdht';
	const PLUGIN_NAME = 'DhbwTraining';

	/**
	 * @var ilDhbwTrainingPlugin
	 */
	protected static $instance;


	/**
	 * @return ilDhbwTrainingPlugin
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	function getPluginName() {
		return self::PLUGIN_NAME;
	}


	protected function uninstallCustom() {
		// TODO: Implement uninstallCustom() method.
	}
}