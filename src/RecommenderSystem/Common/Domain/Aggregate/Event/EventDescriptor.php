<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Event;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\Guid;

class EventDescriptor
{

    /**
     * @var int
     */
    protected $id;
    /**
     * @var Event
     */
    protected $event;
    /**
     * @var int;
     */
    protected $version;


    private function __construct()
    {

    }


    public static function new(int $id, Event $event, int $version) : EventDescriptor
    {
        $obj = new self();
        $obj->id = $id;
        $obj->event = $event;
        $obj->version = $version;

        return $obj;
    }


    /**
     * @return Guid
     */
    public function getId() : int
    {
        return $this->id;
    }


    /**
     * @return Event
     */
    public function getEvent() : Event
    {
        return $this->event;
    }


    /**
     * @return int
     */
    public function getVersion() : int
    {
        return $this->version;
    }
}