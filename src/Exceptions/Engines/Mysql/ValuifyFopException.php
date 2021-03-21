<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Engines\Mysql;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Can not valuify this value.
 */
class ValuifyFopException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Can not valuify this value.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'value',
    ];
}
