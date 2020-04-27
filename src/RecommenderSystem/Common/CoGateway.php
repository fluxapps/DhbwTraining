<?php
declare(strict_types=1);

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\AggregateRepository;

/**
 * Class RcSGateway
 *
 * @package srag\Plugins\DhbwTraining\RecommenderSystem\Common
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Martin Studer <ms@studer-raimann.ch>
 */
abstract class CoGateway
{

    /**
     * @var CoGateway
     */
    protected static $instance;
    /**
     * @var AggregateRepository
     */
    protected $aggregate_repository;


    /**
     * CoGateway constructor.
     */
    protected function __construct(AggregateRepository $aggregate_repository)
    {
        $this->aggregate_repository = $aggregate_repository;
    }


    /**
     * @return CoGateway
     */
    public static function new() : CoGateway
    {
        if (self::$instance === null) {
            self::$instance = new static(static::getDefaultRepository());
        }

        return self::$instance;
    }


    /**
     * @return CoGateway
     */
    public static function newWithCustomRepository(AggregateRepository $aggregate_repository) : CoGateway
    {
        if (self::$instance === null) {
            self::$instance = new static($aggregate_repository);
        }

        return self::$instance;
    }


    abstract protected static function getDefaultRepository() : AggregateRepository;
}
