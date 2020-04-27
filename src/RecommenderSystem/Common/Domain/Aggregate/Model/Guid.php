<?php

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Model;

use Ramsey\Uuid\Uuid;
use srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Exception\InvalidIdException;

final class Guid
{

    /**
     * @var string
     */
    protected $id;


    private function __construct(string $id)
    {
        $this->id = $id;
    }


    /**
     * @throws InvalidIdException
     */
    public static function new(string $id) : self
    {
        self::isValidUuid($id);

        return new static(Uuid::uuid4()->toString());
    }


    /**
     * @throws InvalidIdException
     */
    private static function isValidUuid(string $id)
    {
        if (!preg_match('/^\{?[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}\}?$/', $id)) {
            throw InvalidIdException::forId($id);
        }
    }


    public function __toString() : string
    {
        return $this->id;
    }
}