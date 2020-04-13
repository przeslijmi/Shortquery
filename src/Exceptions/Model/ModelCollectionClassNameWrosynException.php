<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * Model has wrong collection class name.
 */
class ModelCollectionClassNameWrosynException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param string         $collectionClassName Given wrong collection class name.
     * @param Model          $model               Model that has the problem.
     * @param Exception|null $cause               Exception that caused the problem.
     */
    public function __construct(string $collectionClassName, Model $model, ?Exception $cause = null)
    {

        $this->setCodeName('ModelCollectionClassNameWrosynException');
        $this->addInfo('context', 'DefiningModel');
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('givenCollectionClassName', $collectionClassName);
        $this->addInfo('hint', 'Given collection class name is not proper proper class name (see regex below).');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
