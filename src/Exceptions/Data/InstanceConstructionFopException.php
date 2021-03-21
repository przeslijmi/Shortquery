<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Data;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Creating of instance somehow failed.
 */
class InstanceConstructionFopException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Creating of instance somehow failed.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'className',
    ];
}
