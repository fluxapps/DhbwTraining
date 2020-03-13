<?php

use srag\DIC\DhbwTraining\DICTrait;

/**
 * Class xdhtPageObjectConfig
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class xdhtPageObjectConfig extends ilPageConfig
{

    use DICTrait;
    const PLUGIN_CLASS_NAME = ilDhbwTrainingPlugin::class;


    /**
     * @inheritDoc
     */
    public function init()/*:void*/
    {
        // config
        $this->setPreventHTMLUnmasking(true);
        $this->setEnableInternalLinks(false);
        $this->setEnableWikiLinks(false);
        $this->setEnableActivation(false);
    }
}
