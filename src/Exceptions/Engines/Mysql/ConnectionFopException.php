<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Engines\MySql;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Connection has not been established.
 */
class ConnectionFopException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Connection has not been established.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'errorNo',
        'error',
        'host',
        'user',
        'usingPassword',
        'database',
        'port',
    ];
}
