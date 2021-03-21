<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Tryed to crete query type for model, but failed. See cause.
 */
class ModelQueryCreationFailedException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Tryed to crete query type for model, but failed. See cause.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'modelName',
        'modelClass',
        'queryType',
    ];
}
