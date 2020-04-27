<?php

declare(strict_types=1);

namespace srag\Plugins\DhbwTraining\RecommenderSystem\Common\Domain\Aggregate\Exception;

use Exception;

final class InvalidIdException extends Exception
{

    public static function forId(string $id) : self
    {
        return new self(
            'Invalid id: ' . $id
        );
    }
}