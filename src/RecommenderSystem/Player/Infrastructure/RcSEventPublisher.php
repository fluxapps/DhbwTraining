<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Player\Infrastructure;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Infrastructure\Aggregate\AbstractAggregateEventPublisher;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Domain\TrainingSession\Event\TrainingSessionEventPublisher;

class RcSEventPublisher extends AbstractAggregateEventPublisher implements TrainingSessionEventPublisher
{

}