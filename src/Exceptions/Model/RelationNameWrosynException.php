<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Relation;
use Throwable;

/**
 * Relation has wrong name.
 */
class RelationNameWrosynException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param string         $name     Given wrong name.
     * @param Relation       $relation Relation that has the problem.
     * @param Throwable|null $cause    Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $name, Relation $model, ?Throwable $cause = null)
    {

        $this->addInfo('context', 'DefiningModel');
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('givenName', $name);
        $this->addInfo('hint', 'Given name of Relation is not proper. See causes.');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
