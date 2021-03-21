<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Given value for Field is inproper.
 */
class FieldValueInproperException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Given value for Field is inproper.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'warning',
        'field',
        'fieldName',
        'value',
        'model',
        'modelName',
    ];
}
