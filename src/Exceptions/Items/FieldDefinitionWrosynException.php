<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Sexception;

/**
 * You\'re trying to create Field with wrong type.
 */
class FieldDefinitionWrosynException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'You\'re trying to create Field with wrong type.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'error',
        'field',
        'fieldName',
    ];
}
