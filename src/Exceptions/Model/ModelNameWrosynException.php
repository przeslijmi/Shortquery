<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * Model has wrong name.
 */
class ModelNameWrosynException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param string         $name  Given wrong name.
     * @param Model          $model Model that has the problem.
     * @param Throwable|null $cause Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $name, Model $model, ?Throwable $cause = null)
    {

        $this->setCodeName('ModelNameWrosynException');
        $this->addInfo('context', 'DefiningModel');
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('givenName', $name);
        $this->addInfo('hint', 'Given name is not proper proper model name (see regex below).');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
