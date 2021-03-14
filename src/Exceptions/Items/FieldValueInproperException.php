<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Shortquery\Data\Field;

/**
 * Value for given Field is not proper.
 */
class FieldValueInproperException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param mixed $value Contents of value.
     * @param Field $field Field that have a problem.
     */
    public function __construct($value, Field $field)
    {

        // Define.
        $this->addInfo('context', 'definingValueForField');
        $this->addInfo('model', get_class($field->getModel()));
        $this->addInfo('modelName', $field->getModel()->getName());
        $this->addInfo('field', get_class($field));
        $this->addInfo('fieldName', $field->getName());
        $this->addInfo('value', (string) $value);
        $this->addHint('Given value for Field is inproper. ' . $field->getProperValueHint());
    }
}
