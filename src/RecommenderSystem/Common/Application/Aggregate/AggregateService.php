<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Application\Aggregate;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\AggregateRepository;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Domain\TrainingSession\TrainingSessionRepository;

abstract class AggregateService
{

    protected static $instance;
    /**
     * @var AggregateRepository
     */
    protected $aggregate_repository;


    private function __construct(AggregateRepository $aggregate_repository)
    {
        $this->aggregate_repository = $aggregate_repository;
    }


    public static function new(AggregateRepository $aggregate_repository) : self
    {
        if (self::$instance === null) {
            self::$instance = new static($aggregate_repository);
        }

        return self::$instance;
    }
}
