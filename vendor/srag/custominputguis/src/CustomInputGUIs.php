<?php

namespace srag\CustomInputGUIs\DhbwTraining;

use srag\CustomInputGUIs\DhbwTraining\ViewControlModeUI\ViewControlModeUI;
use srag\DIC\DhbwTraining\DICTrait;

/**
 * Class CustomInputGUIs
 *
 * @package srag\CustomInputGUIs\DhbwTraining
 */
final class CustomInputGUIs
{

    use DICTrait;

    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * CustomInputGUIs constructor
     */
    private function __construct()
    {

    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @return ViewControlModeUI
     */
    public function viewControlMode() : ViewControlModeUI
    {
        return new ViewControlModeUI();
    }
}
