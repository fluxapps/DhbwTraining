<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Player\Infrastructure;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Aggregate;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\Guid;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Infrastructure\Aggregate\AbstractAggregateRepository;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Domain\TrainingSession\TrainingSession;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Domain\TrainingSession\TrainingSessionRepository;

class RcSRepository extends AbstractAggregateRepository implements TrainingSessionRepository
{
    final public function getById(int $guid)
    {
        $aggregate_root = new TrainingSession();
        $events = $this->storage->getEventsForAggregate($guid);
        if(count($events) > 0) {
            $aggregate_root->loadsFromHistory($events);

            return $aggregate_root;
        }
      return false;
    }
}