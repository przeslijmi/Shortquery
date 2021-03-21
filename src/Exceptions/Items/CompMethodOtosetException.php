<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Sexception;

/**
 * This comparison method does not exists.
 */
class CompMethodOtosetException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'This comparison method does not exists.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'existing',
        'chosen',
    ];
}
