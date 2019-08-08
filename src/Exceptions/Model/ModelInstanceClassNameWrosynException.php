<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * Model has wrong instance class name.
 */
class ModelInstanceClassNameWrosynException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param string         $instanceClassName Given wrong instance class name.
     * @param Model          $model             Model that has the problem.
     * @param Exception|null $cause             Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $instanceClassName, Model $model, ?Exception $cause = null)
    {

        $this->setCodeName('ModelInstanceClassNameWrosynException');
        $this->addInfo('context', 'DefiningModel');
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('givenInstanceClassName', $instanceClassName);
        $this->addInfo('hint', 'Given instance class name is not proper proper class name (see regex below).');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
