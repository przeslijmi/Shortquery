<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Engines\MySql;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Query has failed (wrong syntax).
 */
class QueryFopException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Query has failed (wrong syntax).';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'query',
        'errorNo',
        'error',
    ];
}
