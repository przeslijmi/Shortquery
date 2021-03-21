<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Sexception;

/**
 * There is no given key in this dictionary.
 */
class FieldDictValueDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'There is no given key in this dictionary.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'field',
        'fieldName',
        'dictName',
        'valueSearched',
        'valuesPresent',
        'model',
        'modelName',
    ];
}
