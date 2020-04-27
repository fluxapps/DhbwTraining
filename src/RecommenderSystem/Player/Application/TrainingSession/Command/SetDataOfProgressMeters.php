<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Player\Application\TrainingSession\Command;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Application\Aggregate\Command\AbstractCommand;


class SetDataOfProgressMeters extends AbstractCommand
{

    protected $version = 1.0;
    /**
     * @var array
     */
    protected $progress_meters;


    public static function new(
        int $guid,
        int $created_by_user_int_id,
        array $progress_meters
    ) {
        $obj = new static();
        $obj->guid = $guid;
        $obj->created_by_user_int_id = $created_by_user_int_id;
        $obj->progress_meters = $progress_meters;

        return $obj;
    }


    /**
     * @return array
     */
    public function getProgressMeters() : array
    {
        return $this->progress_meters;
    }


    /**
     * @return int
     */
    public function getGuid() : int
    {
        return $this->guid;
    }


    /**
     * @return int
     */
    public function getCreatedByUserIntId() : int
    {
        return $this->created_by_user_int_id;
    }


    /**
     * @return int
     */
    public function getVersion() : int
    {
        return $this->version;
    }
}