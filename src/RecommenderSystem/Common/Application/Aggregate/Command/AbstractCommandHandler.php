<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Application\Aggregate\Command;


use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\AggregateRepository;

abstract class AbstractCommandHandler
{

    /**
     * @var AggregateRepository
     */
    protected $command_repository;


    private function __construct(AggregateRepository $command_repository)
    {
        $this->command_repository = $command_repository;
    }

    public static function new(AggregateRepository $command_repository):AbstractCommandHandler
    {
        $obj = new static($command_repository);
        return $obj;
    }

    abstract public function handle(AbstractCommand $command);
}