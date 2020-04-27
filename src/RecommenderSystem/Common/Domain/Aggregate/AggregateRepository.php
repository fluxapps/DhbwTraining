<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\Guid;

interface AggregateRepository
{
    public static function new(AggregateEventStore $storage): AggregateRepository;

    public function getById(int $guid);


    public function save(Aggregate $aggregate_root, int $expected_version); /*: void;*/
}