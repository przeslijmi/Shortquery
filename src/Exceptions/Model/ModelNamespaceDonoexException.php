<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Model has no given namespace, use `$model->setNamespace($nonEmptyNamespace)` to fix.
 */
class ModelNamespaceDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Model has no given namespace, use `$model->setNamespace($nonEmptyNamespace)` to fix.';

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
