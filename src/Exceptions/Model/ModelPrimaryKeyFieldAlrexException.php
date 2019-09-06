<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * There are more than one Primary Key Fields in this Model.
 */
class ModelPrimaryKeyFieldAlrexException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param Model          $model Model that has the problem.
     * @param Throwable|null $cause Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(Model $model, ?Throwable $cause = null)
    {

        $this->addInfo('context', 'DefiningModel');
        $this->addInfo('modelName', $model->getName());
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('hint', 'Model has no two or more Primary Key Fields. It is impossible in this version of Shoq?');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
