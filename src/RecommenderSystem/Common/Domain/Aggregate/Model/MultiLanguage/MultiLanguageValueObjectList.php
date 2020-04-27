<?php

namespace cqrs\Domain\Kernel\Model\MultiLanguageValueObject;

use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\AbstractValueObject;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model\MultiLanguage\MultiLanguageValueObject;

class MultiLanguageValueObjectList extends AbstractValueObject
{

    /**
     * @var MultiLanguageValueObject[]
     */
    protected $multilanguage_value_objects;


    /**
     * @param MultiLanguageValueObject[]
     */
    public static function new(array $multilanguage_value_objects) : MultiLanguageValueObjectList
    {
        $obj = new self();

        if (count($multilanguage_value_objects) > 0) {
            foreach ($multilanguage_value_objects as $multilanguage_value_object) {
                $obj->addValueObject($multilanguage_value_object);
            }
        }

        return $obj;
    }


    private function addValueObject(multiLanguageValueObject $multilanguage_value_object) : void
    {
        $this->multilanguage_value_objects[$multilanguage_value_object->getLngKey()] = $multilanguage_value_object;
    }


    /**
     * @return MultiLanguageValueObject[]
     */
    final function getMultilanguagevalueobjects() : array
    {
        return $this->multilanguage_value_objects;
    }
}