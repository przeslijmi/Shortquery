<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Sexception;

/**
 * When func parameter count is fewer than the desired one.
 */
class FuncToStringItemsToFewException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'When func parameter count is fewer than the desired one.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'funcName',
        'itemsNeededAtLeast',
        'itemsGiven',
    ];
}
