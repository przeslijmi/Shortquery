<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Sexception;

/**
 * When given function name is not present
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class FuncNameOtosetException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'When given function name is not present';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'range',
        'funcName',
    ];
}
