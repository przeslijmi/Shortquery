<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Model has no Field with given name - maybe a Field name or Model name mismatch?
 */
class ModelFieldDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Model has no Field with given name - maybe a Field name or Model name mismatch?';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'modelName',
        'modelClass',
        'fieldName',
    ];
}
