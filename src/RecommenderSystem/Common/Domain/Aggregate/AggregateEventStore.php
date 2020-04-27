<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Event\Event;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Event\AggregateEventPublisher;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\Guid;

interface AggregateEventStore
{

    public static function new(AggregateEventPublisher $event_publisher) : AggregateEventStore;


    public function saveEvents(int $guid, array $events, int $expected_version);


    /**
     * @param int $guid
     *
     * @return Event[]
     */
    public function getEventsForAggregate(int $guid) : array;
}