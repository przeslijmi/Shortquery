<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Relation;

/**
 * There is no FieldTo in this Model.
 */
class RelationFieldToDonoexException extends ClassFopException
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
        $this->addInfo('hint', 'Relation has no defined model from. Use `$relation->setFieldTo()`.');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
