<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Player\Application\TrainingSession\Command;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Application\Aggregate\Command\AbstractCommand;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Application\Aggregate\Command\AbstractCommandHandler;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\AggregateRepository;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Domain\TrainingSession\TrainingSessionRepository;

class SetDataOfProgressMeterHandler extends AbstractCommandHandler
{
    /**
     * @var TrainingSessionRepository
     */
    protected $command_repository;


    /**
     * @param SetDataOfProgressMeters $command
     */
    public function handle(AbstractCommand $command)
    {
        $training_session = $this->command_repository->getById($command->getGuid());
        if($training_session!== false) {
            if($training_session->getGuid()) {
                $training_session->changeProgressMeterList(
                    $command->getProgressMeters()
                );
                $this->command_repository->save($training_session,$command->getVersion());
            }
        }
    }
}