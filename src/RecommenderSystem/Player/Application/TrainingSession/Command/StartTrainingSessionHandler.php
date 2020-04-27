<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Player\Application\TrainingSession\Command;



use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Application\Aggregate\Command\AbstractCommand;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Application\Aggregate\Command\AbstractCommandHandler;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Domain\TrainingSession\TrainingSession;

class StartTrainingSessionHandler extends AbstractCommandHandler
{
    public function handle(AbstractCommand $command)
    {
        $training_session = new TrainingSession();

        $training_session->startTrainingSession(
            $command->guid(),
            $command->getCreatedByUserIntId()
        );

        $this->command_repository->save($training_session,-1);
    }
}