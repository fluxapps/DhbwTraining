<?php

namespace srag\CustomInputGUIs\DhbwTraining;

/**
 * Trait CustomInputGUIsTrait
 *
 * @package srag\CustomInputGUIs\DhbwTraining
 */
trait CustomInputGUIsTrait
{

    /**
     * @return CustomInputGUIs
     */
    protected static final function customInputGUIs() : CustomInputGUIs
    {
        return CustomInputGUIs::getInstance();
    }
}
