<?php

use srag\DIC\DhbwTraining\DICTrait;

/**
 * Class xdhtPageObject
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class xdhtPageObject extends ilPageObject
{

    use DICTrait;
    const PLUGIN_CLASS_NAME = ilDhbwTrainingPlugin::class;
    const PARENT_TYPE = ilDhbwTrainingPlugin::PLUGIN_PREFIX;


    /**
     * @inheritDoc
     */
    public function getParentType() : string
    {
        return self::PARENT_TYPE;
    }
}
