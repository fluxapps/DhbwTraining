<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Infrastructure\Aggregate;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\AggregateEventStore;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Event\AggregateEventPublisher;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Event\Event;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Event\EventDescriptor;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\Guid;


abstract class AbstractAggregateEventStore implements AggregateEventStore
{

    /**
     * @var AggregateEventPublisher
     */
    private $event_publisher;
    /**
     * EventDescriptor[]
     */
    private $event_descriptors = [];


    /**
     * EventStore constructor.
     */
    private function __construct()
    {

    }


    public static function new(AggregateEventPublisher $event_publisher): AggregateEventStore
    {
        $obj = new static();
        $obj->event_publisher = $event_publisher;

        return $obj;
    }


    /**
     * @param int    $aggregate_id
     * @param Event[] $events
     * @param int     $expected_version
     *
     * @return mixed|void
     */
    public function saveEvents(int $aggregate_id, array $events, int $expected_version)
    {

        $i = $expected_version;

        foreach ($events as $event) {
            $i++;

            $this->event_descriptors[] = EventDescriptor::new(
                $aggregate_id,
                $event,
                $i
            );

            // publish current event to the bus for further processing by subscribers
            $this->event_publisher->publish($event);
        }
    }


    /**
     * @param int $aggregate_id
     *
     * @return Event[]
     */
    public function getEventsForAggregate(int $guid) : array
    {
        $arr_events = [];
        foreach($this->event_descriptors as $event_descriptor) {

            if($event_descriptor->getId() === $guid) {
                $arr_events[] = $event_descriptor->getEvent();
            }

        }
        return $arr_events;
    }
}