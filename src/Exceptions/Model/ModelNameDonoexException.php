<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * Model name is empty.
 */
class ModelNameDonoexException extends ClassFopException
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

        $this->setCodeName('ModelNameDonoexException');
        $this->addInfo('context', 'DefiningModel');
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('hint', 'Model has no given name, use `$model->setName($nonEmptyName)` to fix.');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}