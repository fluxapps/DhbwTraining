<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model;

interface ValueObjectList extends ValueObject
{

    /**
     * @return ValueObjectList
     */
    public function getList() : array;


    /**
     * @var ValueObjectList $int_value_object
     */
    public function setList(ValueObjectList $int_value_object); /*void*/

    /**
     * @return ValueObject
     */
   // public function append(AbstractValueObject $value_object); /*void*/
}