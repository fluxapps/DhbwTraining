<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Player\Domain\TrainingSession;

use DataOfProgressMetersSet;
use ilObjFolderGUI;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Aggregate;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\Guid;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Domain\TrainingSession\Event\TrainingSessionStarted;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Domain\TrainingSession\Model\ProgressMeter\ProgressMeter;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Domain\TrainingSession\Model\ProgressMeter\ProgressMeterList;

class TrainingSession extends Aggregate
{

    /**
     * @var Guid
     */
    protected $guid;
    /**
     * @var ProgressMeterList
     */
    protected $progress_meter_list;


    public static function new(Guid $guid) : TrainingSession
    {
      //  $obj = new ilObjFolderGUI();
        $obj->guid = $guid;

        return $obj;
    }

    public function getProgressMeterList() {
        return $this->progress_meter_list;
    }


    public function startTrainingSession(
        Guid $guid,
        int $created_by_usr_id
    ) /* : void*/
    {
        $this->applyEvent(new TrainingSessionStarted($guid, $created_by_usr_id), true);
    }


    public function applyTrainingSessionStarted(TrainingSessionStarted $event) /*: void*/
    {
        $this->guid = $event->getGuid();
        $this->created_by_usr_id = $event->getCreatedByUsrId();
    }


    public function changeProgressMeterList(array $arr_progress_meters)
    {
        if (count($arr_progress_meters) > 0) {
            $progress_meters = [];
            foreach ($arr_progress_meters as $arr_progress_meter) {
                $progress_meters[] = ProgressMeter::newFromArray($arr_progress_meter);
            }

            $changed = false;
            if (is_object($this->getProgressMeterList()) && count($this->getProgressMeterList()->getList())) {
                foreach ($this->getProgressMeters()->getList() as $progress_meter) {
                    $changed = true;
                }
            }

            if ($changed) {
                $this->applyEvent(new DataOfProgressMetersSet($this->guid, $this->getCreatedByUsrId(), $this->getProgressMeters()->getList()), true);
            }
        }
    }


    public function applyDataOfProgressMetersSet(DataOfProgressMetersSet $event)
    {

        $this->guid = $event->getGuid();
        $this->created_by_usr_id = $event->getCreatedByUsrId();
        foreach ($event->getProgressMeters() as $progress_meter) {
            $this->progress_meter_list->add($progress_meter);
        }
    }


    /**
     * @param ProgressMeterList $progress_meter_list
     */
    public function setProgressMeterList(
        ProgressMeterList $progress_meter_list
    ) {
        $this->progress_meter_list = $progress_meter_list;
    }
}