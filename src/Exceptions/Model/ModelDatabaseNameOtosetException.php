<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * There is no database with that name.
 */
class ModelDatabaseNameOtosetException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'There is no database with that name.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'range',
        'actualValue',
        'modelName',
        'modelClass',
    ];
}
