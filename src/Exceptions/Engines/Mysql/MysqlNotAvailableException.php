<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Engines\Mysql;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Server has not even answered.
 */
class MysqlNotAvailableException extends Sexception
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
