<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Player\Application\TrainingSession\Command;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Application\Aggregate\Command\AbstractCommand;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\Guid;

class StartTrainingSession extends AbstractCommand
{
    protected $version = 1.0;

    public static function new(
        int $guid,
        int $created_by_user_int_id)
    {
        $obj = new static();
        $obj->guid = $guid;
        $obj->created_by_user_int_id = $created_by_user_int_id;

        return $obj;
    }


    /**
     * @return Int
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