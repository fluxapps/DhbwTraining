<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Infrastructure\Aggregate;

use ilObjDhbwTraining;
use ilSession;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Event\AggregateEventPublisher;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Event\Event;


abstract class AbstractAggregateEventPublisher implements AggregateEventPublisher
{

    public static function new() : AggregateEventPublisher
    {
        return new static();
    }


    public function publish(Event $event)
    {

    }
}