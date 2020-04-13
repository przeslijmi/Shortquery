<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * Model has wrong namespace.
 */
class ModelNamespaceWrosynException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param string         $namespace Given wrong namespace.
     * @param Model          $model     Model that has the problem.
     * @param Exception|null $cause     Exception that caused the problem.
     */
    public function __construct(string $namespace, Model $model, ?Exception $cause = null)
    {

        $this->setCodeName('ModelNamespaceWrosynException');
        $this->addInfo('context', 'DefiningModel');
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('givenNamespace', $namespace);
        $this->addInfo('hint', 'Given namespace is not proper proper model namespace (see regex below).');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
