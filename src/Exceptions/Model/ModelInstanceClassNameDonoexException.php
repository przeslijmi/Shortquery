<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Exception;
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
     * @param Model          $model Model that has the problem.
     * @param Exception|null $cause Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(Model $model, ?Exception $cause = null)
    {

        // Lvd.
        $hint  = 'Model has no given instance class name,';
        $hint .= ' use `$model->setInstanceClassName($nonEmptyInstanceClassName)` to fix.';

        // Define
        $this->setCodeName('ModelInstanceClassNameDonoexException');
        $this->addInfo('context', 'DefiningModel');
        $this->addInfo('modelName', $model->getName());
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('hint', $hint);

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
