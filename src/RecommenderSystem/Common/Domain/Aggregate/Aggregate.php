<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Event\Event;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\Guid;

class Aggregate
{

    const APPLY_PREFIX = "apply";
    /**
     * @var int
     */
    protected $guid;
    /**
     * @var int
     */
    protected $created_by_usr_id;
    /**
     * @var int
     */
    protected $version;
    /**
     * @var Event[]
     */
    protected $events;


    /**
     * @return int
     */
    final public function getGuid() : int
    {
        return $this->guid;
    }


    /**
     * @return int
     */
    public function getCreatedByUsrId() : int
    {
        return $this->created_by_usr_id;
    }


    /**
     * @return int
     */
    public function getVersion() : int
    {
        return $this->version;
    }


    /**
     * @return Event[]
     */
    public function getEvents() : array
    {
        return $this->events;
    }




    final public function getUncommittedEvents() : array
    {
        return $this->events;
    }


    final public function markEventsAsCommitted() /*:void*/
    {
        $this->events = [];
    }


    /**
     * @param Event[] $history
     */
    final public function loadsFromHistory(array $history) /*:void*/
    {
        foreach ($history as $event) {
            $this->applyEvent($event, false);
        }
    }


    final protected function applyEvent(Event $event, bool $is_new)/*:void*/
    {
        $action_handler = $this->getHandlerName($event);
        if (method_exists($this, $action_handler)) {
            $this->$action_handler($event);
        }

        if ($is_new) {
            $this->events[$event->getVersion()] = $event;
        }
    }


    final public function getHandlerName(Event $event) : string
    {
        return self::APPLY_PREFIX . join('', array_slice(explode('\\', get_class($event)), -1));
    }
}