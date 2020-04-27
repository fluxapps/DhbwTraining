<?php
declare(strict_types=1);

namespace srag\Plugins\DhbwTraining\RecommenderSystem;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\CoGateway;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\AggregateRepository;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Application\TrainingSession\TrainingSessionService;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Infrastructure\RcSEventPublisher;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Infrastructure\RcSEventStore;
use srag\Plugins\DhbwTraining\RecommenderSystem\Player\Infrastructure\RcSRepository;

/**
 * Class CoGateway
 *
 * @package srag\Plugins\SrDddDemoPlugin\AvlLms
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Martin Studer <ms@studer-raimann.ch>
 */
class RcSGateway extends CoGateway
{

    /**
     * @var RcSGateway
     */
    protected static $instance;

    /**
     * RcSGateway constructor.
     */
    protected function __construct(AggregateRepository $aggregate_repository) {
        parent::__construct($aggregate_repository);
    }



    final public function trainingSession() : TrainingSessionService
    {
        return TrainingSessionService::new($this->aggregate_repository);
    }


    protected static function getDefaultRepository() : AggregateRepository
    {
        return RcSRepository::new(RcSEventStore::new(RcSEventPublisher::new()));
    }
}
