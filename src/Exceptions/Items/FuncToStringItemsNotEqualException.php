<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Sexception;

/**
 * When func parameter count is not equal to desired one.
 */
class FuncToStringItemsNotEqualException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'When func parameter count is not equal to desired one.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'funcName',
        'itemsNeeded',
        'itemsGiven',
    ];
}
