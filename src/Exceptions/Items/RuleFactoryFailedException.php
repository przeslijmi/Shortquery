<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * Rule could not be created because factory failed.
 */
class RuleFactoryFailedException extends ClassFopException
{

    /**
     * Constructor.
     */
    public function __construct()
    {

        // Define.
        $this->addHint('Rule Factory failed its operation - given settings for Rule were inproper. See causes.');
    }
}
