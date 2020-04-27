<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Player\Domain\TrainingSession\Event;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Event\Event;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\Guid;

class TrainingSessionStarted extends Event
{
    /**
     * @var int
     */
    protected $version = -1;

    const NAME = 'TrainingSessionStarted';


    public function __construct(
        int $guid,
        int $created_by_usr_id
    ) {
        $this->setGuid($guid);
        $this->setCreatedByUsrId($created_by_usr_id);
    }

    final public function getEventName() : string
    {
        return self::NAME;
    }


    /**
     * @param int $version
     */
    final protected function setVersion(int $version)
    {
        $this->version = $version;
    }


    /**
     * @param int $created_by_usr_id
     */
    final protected function setCreatedByUsrId(int $created_by_usr_id)
    {
        $this->created_by_usr_id = $created_by_usr_id;
    }


    /**
     * @param int $guid
     */
    final protected function setGuid(int $guid)
    {
        $this->guid = $guid;
    }


}