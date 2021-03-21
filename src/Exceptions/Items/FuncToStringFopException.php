<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Converting function to string failed.
 */
class FuncToStringFopException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Converting function to string failed.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'funcName',
    ];
}
