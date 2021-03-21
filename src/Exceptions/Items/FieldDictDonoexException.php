<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Sexception;

/**
 * You\'re trying to get dictionary that does not exists.
 */
class FieldDictDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'You\'re trying to get dictionary that does not exists.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'field',
        'fieldName',
        'dictSearched',
        'dictsPresent',
        'model',
        'modelName',
    ];
}
