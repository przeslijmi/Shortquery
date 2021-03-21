<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Somehow failed to reach Field dictionary value. See cause.
 */
class FieldDictValueUnaccesibleException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Somehow failed to reach Field dictionary value. See cause.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'model',
        'fieldName',
        'dictName',
        'value',
    ];
}
