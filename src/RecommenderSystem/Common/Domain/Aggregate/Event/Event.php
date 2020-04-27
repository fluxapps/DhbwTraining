<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Event;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\Guid;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Application\Message;

abstract class Event implements Message
{

    /**
     * @var Guid
     */
    protected $guid;
    /**
     * @var int
     */
    protected $created_by_usr_id;
    /**
     * @var int
     */
    protected $version;


    /**
     * @return Guid
     */
    public function getGuid() : Guid
    {
        return $this->guid;
    }


    /**
     * @return int
     */
    public function getCreatedByUsrId() : int
    {
        return $this->created_by_usr_id;
    }


    /**
     * @return int
     */
    public function getVersion() : int
    {
        return $this->version;
    }


    /**
     * @param Guid $guid
     */
    protected function setGuid(Guid $guid)
    {
        $this->guid = $guid;
    }

    abstract public function getEventName():string;


    /**
     * @param int $created_by_usr_id
     */
    abstract protected function setCreatedByUsrId(int $created_by_usr_id);


    /**
     * @param int $version
     */
    abstract protected function setVersion(int $version);
}