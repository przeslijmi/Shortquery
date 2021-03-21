<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Model has no given name, use `$model->setName($nonEmptyName)` to fix.
 */
class ModelNameDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Model has no given name, use `$model->setName($nonEmptyName)` to fix.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'modelClass',
    ];
}
