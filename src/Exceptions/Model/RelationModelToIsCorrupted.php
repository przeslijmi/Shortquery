<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Relation;

/**
 * ModelTo in Relation is corrupted - can not be created (instantiated).
 */
class RelationModelToIsCorrupted extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param Relation       $relation Relation that has the problem.
     * @param Throwable|null $cause    Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(Relation $relation, ?Throwable $cause = null)
    {

        $this->addInfo('context', 'DefiningRelation');
        $this->addInfo('relationName', $relation->getName());
        $this->addInfo('relationClass', get_class($relation));
        $this->addInfo('hint', 'Relation has ModelTo defined but when tries to instantiate it ... fails. See causes.');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
