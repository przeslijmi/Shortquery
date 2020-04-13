<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * Model namespace is empty.
 */
class ModelNamespaceDonoexException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param Model $model Model that has the problem.
     */
    public function __construct(Model $model)
    {

        $this->addInfo('context', 'DefiningModel');
        $this->addInfo('modelName', $model->getName());
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('hint', 'Model has no given namespace, use `$model->setNamespace($nonEmptyNamespace)` to fix.');
    }
}
