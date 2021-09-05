<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Engines\MySql;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Server has not even answered.
 */
class MySqlNotAvailableException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Server has not even answered.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'host',
        'port',
        'user',
        'usingPassword',
        'database',
    ];
}
