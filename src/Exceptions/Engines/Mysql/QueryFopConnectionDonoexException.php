<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Engines\MySql;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Query was not performed because connection has not been established.
 */
class QueryFopConnectionDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Query was not performed because connection has not been established.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'query',
    ];
}
