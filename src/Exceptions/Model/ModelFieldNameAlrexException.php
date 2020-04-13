<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * Model Field name already exists - name's are duplicated.
 */
class ModelFieldNameAlrexException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param string $fieldName Name of the field that duplicates.
     * @param Model  $model     Model that has the problem.
     */
    public function __construct(string $fieldName, Model $model)
    {

        // Lvd.
        $hint  = 'You\'re trying to add next Field to the model - but the name is already taken';
        $hint .= 'by another Field in this Model. Model can\'t have two or more Fields with the same name.';

        // Define.
        $this->setCodeName('ModelFieldNameAlrexException');
        $this->addInfo('context', 'DefiningModel');
        $this->addInfo('modelName', $model->getName());
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('fieldName', $fieldName);
        $this->addHint($hint);
    }
}
