<?php

namespace srag\Plugins\DhbwTraining\Config;

use ilCheckboxInputGUI;
use ilDhbwTrainingPlugin;
use ilTextInputGUI;
use srag\ActiveRecordConfig\DhbwTraining\ActiveRecordConfigFormGUI;

/**
 * Class ConfigFormGUI
 *
 * @package srag\Plugins\DhbwTraining\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ConfigFormGUI extends ActiveRecordConfigFormGUI
{

    const PLUGIN_CLASS_NAME = ilDhbwTrainingPlugin::class;
    const CONFIG_CLASS_NAME = Config::class;


    /**
     * @inheritdoc
     */
    protected function initFields()/*: void*/
    {
        $this->fields = [
            Config::KEY_LEARNING_PROGRESS => [
                self::PROPERTY_CLASS => ilCheckboxInputGUI::class,
                "setTitle"           => self::plugin()->translate("learning_progress")
            ],
            Config::KEY_SALT              => [
                self::PROPERTY_CLASS    => ilTextInputGUI::class,
                self::PROPERTY_REQUIRED => true
            ]
        ];
    }
}
