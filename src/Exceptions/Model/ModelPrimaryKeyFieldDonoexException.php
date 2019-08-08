<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * There is no Primary Key Field in this Model.
 */
class ModelPrimaryKeyFieldDonoexException extends ClassFopException
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

        $this->setCodeName('ModelPrimaryKeyFieldDonoexException');
        $this->addInfo('context', 'DefiningModel');
        $this->addInfo('modelName', $model->getName());
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('hint', 'Model has no Primary Key Field - use `$field->setPk(true)` to change it.?');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
