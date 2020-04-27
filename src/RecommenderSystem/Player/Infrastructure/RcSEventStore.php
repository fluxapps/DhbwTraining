<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Player\Infrastructure;

use ilObjDhbwTraining;
use ilSession;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Event\Event;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Event\EventDescriptor;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\Guid;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Infrastructure\Aggregate\AbstractAggregateEventStore;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Domain\TrainingSession\TrainingSessionEventStore;

class RcSEventStore extends AbstractAggregateEventStore implements TrainingSessionEventStore
{
    public function saveEvents(int $guid, array $events, int $expected_version)
    {
        global $DIC;
        parent::saveEvents($guid,$events,$expected_version);

        $old_events = [];
      //  if(is_array(unserialize(ilSession::_getData($guid)))) {
            $old_events[] = unserialize(ilSession::_getData($guid));
      //  }

        foreach($events as  $event) {
            $old_events[] = $event;
        }
    }

    /**
     * @param Guid $aggregate_id
     *
     * @return Event[]
     */
    public function getEventsForAggregate(int $guid) : array
    {
        $arr_events = unserialize(ilSession::_getData($guid));
        if(is_array($arr_events)) {
            return $arr_events;
        }
        return [];
    }


}