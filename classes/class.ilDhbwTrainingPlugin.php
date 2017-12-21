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

	/**
	 * Before activation processing
	 */
	protected function beforeActivation() {
		global $ilDB;
		parent::beforeActivation();

		// check whether type exists in object data, if not, create the type
		$set = $ilDB->query("SELECT * FROM object_data ".
			" WHERE type = ".$ilDB->quote("typ", "text").
			" AND title = ".$ilDB->quote('xdht', "text")
		);
		if ($rec = $ilDB->fetchAssoc($set))
		{
			$t_id = $rec["obj_id"];
		}

		// add rbac operations
		// 1: edit_permissions, 2: visible, 3: read, 4:write, 6:delete
		$ops = array(55, 58, 95);
		foreach ($ops as $op)
		{
			// check whether type exists in object data, if not, create the type
			$set = $ilDB->query("SELECT * FROM rbac_ta ".
				" WHERE typ_id = ".$ilDB->quote($t_id, "integer").
				" AND ops_id = ".$ilDB->quote($op, "integer")
			);
			if (!$ilDB->fetchAssoc($set))
			{
				$ilDB->manipulate("INSERT INTO rbac_ta ".
					"(typ_id, ops_id) VALUES (".
					$ilDB->quote($t_id, "integer").",".
					$ilDB->quote($op, "integer").
					")");
			}
		}
		return true;
	}
}