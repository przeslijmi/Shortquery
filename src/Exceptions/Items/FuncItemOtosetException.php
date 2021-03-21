<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Sexception;

/**
 * When no parameter at given id is present.
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class FuncItemOtosetException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'When no parameter at given id is present.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'properItemsIds',
        'usedItemId',
    ];
}
