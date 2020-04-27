<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Application\Aggregate\Command;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\Guid;

abstract class AbstractCommand
{

    /**
     * @var int
     */
    protected $guid;
    /**
     * @var int
     */
    protected $created_by_user_int_id;
    /**
     * @var int
     */
    protected $version;


    /**
     * @return int
     */
    public abstract function getGuid() : int;


    /**
     * @return int
     */
    public abstract function getCreatedByUserIntId() : int;


    /**
     * @return int
     */
    public abstract function getVersion() : int;
}