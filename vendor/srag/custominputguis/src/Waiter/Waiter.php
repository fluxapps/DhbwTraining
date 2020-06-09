<?php

namespace srag\CustomInputGUIs\DhbwTraining\Waiter;

use srag\DIC\DhbwTraining\DICTrait;

/**
 * Class Waiter
 *
 * @package srag\CustomInputGUIs\DhbwTraining\Waiter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Waiter
{

    use DICTrait;
    /**
     * @var string
     */
    const TYPE_WAITER = "waiter";
    /**
     * @var string
     */
    const TYPE_PERCENTAGE = "percentage";
    /**
     * @var bool
     */
    protected static $init = false;


    /**
     * Waiter constructor
     */
    private function __construct()
    {

    }


    /**
     * @param string $type
     */
    public static final function init(/*string*/ $type)/*: void*/
    {
        if (self::$init === false) {
            self::$init = true;

            $dir = __DIR__;
            $dir = "./" . substr($dir, strpos($dir, "/Customizing/") + 1);

            self::dic()->ui()->mainTemplate()->addCss($dir . "/css/waiter.css");

            self::dic()->ui()->mainTemplate()->addJavaScript($dir . "/js/waiter.min.js");
        }

        self::dic()->ui()->mainTemplate()->addOnLoadCode('il.waiter.init("' . $type . '");');
    }
}
