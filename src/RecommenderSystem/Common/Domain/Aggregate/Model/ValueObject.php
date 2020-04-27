<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model;

use JsonSerializable;

interface ValueObject extends JsonSerializable
{
    public function jsonSerialize();
}