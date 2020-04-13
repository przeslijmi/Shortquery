<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * Model instance class name is empty.
 */
class ModelInstanceClassNameDonoexException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param Model $model Model that has the problem.
     */
    public function __construct(Model $model)
    {

        // Lvd.
        $hint  = 'Model has no given instance class name,';
        $hint .= ' use `$model->setInstanceClassName($nonEmptyInstanceClassName)` to fix.';

        // Define.
        $this->setCodeName('ModelInstanceClassNameDonoexException');
        $this->addInfo('context', 'DefiningModel');
        $this->addInfo('modelName', $model->getName());
        $this->addInfo('modelClass', get_class($model));
        $this->addHint($hint);
    }
}
