<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Infrastructure\Aggregate;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Aggregate;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\AggregateEventStore;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\AggregateRepository;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\Guid;

abstract class AbstractAggregateRepository implements AggregateRepository
{

    /**
     * @var AggregateEventStore
     */
    protected $storage;


    public static function new(AggregateEventStore $storage) : AggregateRepository
    {
        $obj = new static();
        $obj->storage = $storage;

        return $obj;
    }


    abstract public function getById(int $id);


    final public function save(Aggregate $aggregate_root, int $expected_version) /*:void;*/
    {
        $this->storage->saveEvents(
            $aggregate_root->getGuid(),
            $aggregate_root->getUncommittedEvents(),
            $expected_version
        );
    }
}