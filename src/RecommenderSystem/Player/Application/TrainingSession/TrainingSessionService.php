<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Player\Application\TrainingSession;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Application\Aggregate\AggregateService;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\Guid;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Application\TrainingSession\Command\SetDataOfProgressMeterHandler;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Application\TrainingSession\Command\SetDataOfProgressMeters;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Application\TrainingSession\Command\StartTrainingSession;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Application\TrainingSession\Command\StartTrainingSessionHandler;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Domain\TrainingSession\Model\ProgressMeter\ProgressMeterList;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Domain\TrainingSession\TrainingSessionRepository;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Infrastructure\RcSEventPublisher;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Infrastructure\RcSEventStore;

class TrainingSessionService extends AggregateService
{

    /**
     * @var TrainingSessionService
     */
    protected static $instance;


    final public function startTrainingSession(
        int $id,
        int $command_user_int_id
    ) /*void*/
    {
        $comand = StartTrainingSession::new($id, $command_user_int_id);
        StartTrainingSessionHandler::new($this->aggregate_repository)->handle($comand);
    }


    /**
     * @param int $id
     *
     * @return ProgressMeterList|null
     */
    public function getProgressMeterList(
        int $guid
    ) {
        $training_session = $this->aggregate_repository->getById($guid);
        if($training_session != false) {
            return $training_session->getProgressMeterList();
        }
    }


    public function setDataOfProgressMeters(
        int $guid,
        int $command_user_int_id,
        array $progress_meters
    )
    {
        $comand = SetDataOfProgressMeters::new($guid, $command_user_int_id, $progress_meters);
        SetDataOfProgressMeterHandler::new($this->aggregate_repository)->handle($comand);
    }
}
