<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * There is no Field with this name in this Model.
 */
class ModelFieldDonoexException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param string         $fieldName Name of field.
     * @param Model          $model     Model that has the problem.
     * @param Exception|null $cause     Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $fieldName, Model $model, ?Exception $cause = null)
    {

        $this->setCodeName('ModelFieldDonoexException');
        $this->addInfo('context', 'DefiningModel');
        $this->addInfo('modelName', $model->getName());
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('fieldName', $fieldName);
        $this->addInfo('hint', 'Model has no Field with given name - maybe a Field name or Model name mismatch?');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
