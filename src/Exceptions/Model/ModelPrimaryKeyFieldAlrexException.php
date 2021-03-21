<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Model has no two or more Primary Key Fields. It is impossible in this version of Shoq?
 */
class ModelPrimaryKeyFieldAlrexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Model has no two or more Primary Key Fields. It is impossible in this version of Shoq?';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'modelName',
        'modelClass',
    ];
}
