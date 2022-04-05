<?php

namespace srag\RemovePluginDataConfirm\DhbwTraining;

/**
 * Trait PluginUninstallTrait
 *
 * @package srag\RemovePluginDataConfirm\DhbwTraining
 */
trait PluginUninstallTrait
{

    use BasePluginUninstallTrait;

    /**
     * @internal
     */
    protected final function afterUninstall() : void
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
