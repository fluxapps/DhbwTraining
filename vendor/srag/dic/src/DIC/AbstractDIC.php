<?php

namespace srag\DIC\DhbwTraining\DIC;

use ILIAS\DI\Container;
use srag\DIC\DhbwTraining\Database\DatabaseDetector;
use srag\DIC\DhbwTraining\Database\DatabaseInterface;

/**
 * Class AbstractDIC
 *
 * @package srag\DIC\DhbwTraining\DIC
 */
abstract class AbstractDIC implements DICInterface
{

    /**
     * @var Container
     */
    protected $dic;


    /**
     * @inheritDoc
     */
    public function __construct(Container &$dic)
    {
        $this->dic = &$dic;
    }


    /**
     * @inheritDoc
     */
    public function database() : DatabaseInterface
    {
        return DatabaseDetector::getInstance($this->databaseCore());
    }
}
