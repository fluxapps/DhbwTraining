<?php

namespace srag\RemovePluginDataConfirm\DhbwTraining;

/**
 * Trait PluginUninstallTrait
 *
 * @package srag\RemovePluginDataConfirm\DhbwTraining
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait PluginUninstallTrait
{

    use BasePluginUninstallTrait;

    /**
     * @internal
     */
    protected final function afterUninstall()/*: void*/
    {

    }


    /**
     * @return bool
     *
     * @internal
     */
    protected final function beforeUninstall() : bool
    {
        return $this->pluginUninstall();
    }
}
