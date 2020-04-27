<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\MultiLanguage;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\AbstractValueObject;

/**
 * Class MultiLanguageValueObject
 *
 * @package cqrs\Domain\Kernel\Model\MultiLanguageValueObject
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Martin Studer <ms@studer-raimann.ch>
 */
class MultiLanguageValueObject extends AbstractValueObject
{

    /**
     * @var string
     */
    protected $lng_key = "";
    /**
     * @var string
     */
    protected $title = "";


    /**
     * LngString constructor
     */
    protected function __construct()
    {

    }


    /**
     * @param string $lng_key
     * @param string $title
     *
     * @return self
     */
    public static function new(string $lng_key, string $title) : self
    {
        $instance = new self();

        $instance->lng_key = $lng_key;

        $instance->title = $title;

        return $instance;
    }


    /**
     * @return string
     */
    public function getLngKey() : string
    {
        return $this->lng_key;
    }


    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }
}
