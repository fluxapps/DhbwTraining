<?php

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Event\Event;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\Guid;

class DataOfProgressMetersSet extends Event
{
    /**
     * @var int
     */
    protected $version = -1;

    /**
     * @var array
     */
    protected $progress_meters;


    const NAME = 'DataOfProgressMetersSet';


    public function __construct(
        Guid $guid,
        int $created_by_usr_id,
         array $progress_meters
    ) {
        $this->setGuid($guid);
        $this->setCreatedByUsrId($created_by_usr_id);
        $this->setProgressMeters($progress_meters);
    }

    final public function getEventName() : string
    {
        return self::NAME;
    }


    /**
     * @return array
     */
    public function getProgressMeters() : array
    {
        return $this->progress_meters;
    }


    /**
     * @param array $progress_meters
     */
    public function setProgressMeters(array $progress_meters)
    {
        $this->progress_meters = $progress_meters;
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
     * @param Guid $guid
     */
    final protected function setGuid(Guid $guid)
    {
        $this->guid = $guid;
    }

}