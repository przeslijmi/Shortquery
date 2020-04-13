<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * Logic could not be created because factory failed.
 */
class LogicFactoryFailedException extends ClassFopException
{

    /**
     * Constructor.
     */
    public function __construct()
    {

        // Define.
        $this->addHint('Logic Factory failed its operation - given settings for Logic were inproper. See causes.');
    }
}
