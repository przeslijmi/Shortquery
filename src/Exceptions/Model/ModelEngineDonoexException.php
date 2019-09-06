<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * Model has database defined but there is no engine present as said in database.
 */
class ModelEngineDonoexException extends ClassFopException
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
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('hint', 'Model has given database name but its engine is not existing. All engines has to be defined in `PRZESLIJMI_SHORTQUERY_ENGINES`.');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
