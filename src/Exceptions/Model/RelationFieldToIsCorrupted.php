<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Relation;

/**
 * FieldTo in Relation is corrupted - can not be created (instantiated).
 */
class RelationFieldToIsCorrupted extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param Relation       $relation Relation that has the problem.
     * @param null|Throwable $cause    Throwable that caused the problem.
     */
    public function __construct(Relation $relation, ?Throwable $cause = null)
    {

        // Lvd.
        $hint = 'Relation has FieldTo defined but when tries to instantiate it ... fails. See causes.';

        // Define.
        $this->addInfo('context', 'DefiningRelation');
        $this->addInfo('relationName', $relation->getName());
        $this->addInfo('relationClass', get_class($relation));
        $this->addHint($hint);

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
