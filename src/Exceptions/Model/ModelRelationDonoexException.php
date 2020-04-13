<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * There is no Relation with this name in this Model.
 */
class ModelRelationDonoexException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param string $relationName Name of field.
     * @param Model  $model        Model that has the problem.
     */
    public function __construct(string $relationName, Model $model)
    {

        // Lvd.
        $hint = 'Model has no Relation with given name - maybe a Relation name or Model name mismatch?';

        $this->addInfo('context', 'DefiningModel');
        $this->addInfo('modelName', $model->getName());
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('relationName', $relationName);
        $this->addHint($hint);
    }
}
