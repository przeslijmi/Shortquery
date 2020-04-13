<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

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
     * @param Relation $relation Relation that has the problem.
     */
    public function __construct(Relation $relation)
    {

        // Lvd.
        $hint = 'Relation has no defined model from. Use `$relation->setFieldTo()`.';

        // Define.
        $this->addInfo('context', 'DefiningRelation');
        $this->addInfo('relationName', $relation->getName());
        $this->addInfo('relationClass', get_class($relation));
        $this->addHint($hint);
    }
}
