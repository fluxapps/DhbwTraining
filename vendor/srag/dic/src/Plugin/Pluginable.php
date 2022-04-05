<?php

namespace srag\DIC\DhbwTraining\Plugin;

/**
 * Interface Pluginable
 *
 * @package srag\DIC\DhbwTraining\Plugin
 */
interface Pluginable
{

    /**
     * @return PluginInterface
     */
    public function getPlugin() : PluginInterface;


    /**
     * @param PluginInterface $plugin
     *
     * @return static
     */
    public function withPlugin(PluginInterface $plugin)/*: static*/ ;
}
