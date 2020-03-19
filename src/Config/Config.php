<?php

namespace srag\Plugins\DhbwTraining\Config;

use ilDhbwTrainingPlugin;
use Ramsey\Uuid\Uuid;
use srag\ActiveRecordConfig\DhbwTraining\ActiveRecordConfig;

/**
 * Class Config
 *
 * @package srag\Plugins\DhbwTraining\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Config extends ActiveRecordConfig
{

    const TABLE_NAME = "xdht_config";
    const PLUGIN_CLASS_NAME = ilDhbwTrainingPlugin::class;
    const KEY_LEARNING_PROGRESS = "learning_progress";
    const KEY_SALT = "salt";
    /**
     * @var array
     */
    protected static $fields
        = [
            self::KEY_LEARNING_PROGRESS => [self::TYPE_BOOLEAN, true],
            self::KEY_SALT              => [self::TYPE_STRING, ""]
        ];


    /**
     *
     */
    public static function initDefaultSalt()/*:void*/
    {
        if (empty(self::getField(Config::KEY_SALT))) {
            self::setField(Config::KEY_SALT, Uuid::uuid4()->toString());
        }
    }
}
