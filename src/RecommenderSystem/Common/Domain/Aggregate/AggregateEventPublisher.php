<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Event;

interface AggregateEventPublisher
{
    public static function new();
    public function publish(Event $event);
}