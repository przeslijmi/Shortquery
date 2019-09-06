<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * Model Relation name already exists - name's are duplicated.
 */
class ModelRelationNameAlrexException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param string         $relationName Name of the field that duplicates.
     * @param Model          $model        Model that has the problem.
     * @param Exception|null $cause        Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $relationName, Model $model, ?Exception $cause = null)
    {

        // Lvd.
        $hint  = 'You\'re trying to add next Relation to the model - but the name is already taken';
        $hint .= 'by another Relation in this Model. Model can\'t have two or more Relations with the same name.';

        // Define.
        $this->addInfo('context', 'DefiningModel');
        $this->addInfo('modelName', $model->getName());
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('relationName', $relationName);
        $this->addInfo('hint', $hint);

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
