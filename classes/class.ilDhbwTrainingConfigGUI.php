<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\ActiveRecordConfig\DhbwTraining\ActiveRecordConfigGUI;
use srag\Plugins\DhbwTraining\Config\ConfigFormGUI;

/**
 * Class ilDhbwTrainingConfigGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilDhbwTrainingConfigGUI extends ActiveRecordConfigGUI
{

    const PLUGIN_CLASS_NAME = ilDhbwTrainingPlugin::class;
    /**
     * @var array
     */
    protected static $tabs = [self::TAB_CONFIGURATION => ConfigFormGUI::class];
}
