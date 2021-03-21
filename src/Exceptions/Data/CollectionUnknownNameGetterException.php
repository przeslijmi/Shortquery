<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Data;

use Przeslijmi\Sexceptions\Sexception;

/**
 * You\'re calling `getClass` method with wrong first parameter.
 */
class CollectionUnknownNameGetterException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'You\'re calling `getClass` method with wrong first parameter.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'possible',
        'used',
    ];
}
