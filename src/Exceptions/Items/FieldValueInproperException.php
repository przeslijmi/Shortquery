<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Throwable;
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
     * @param mixed          $value Contents of value.
     * @param Field          $field Field that have a problem.
     * @param Throwable|null $cause Throwable that caused the problem.
     *
     * @since v1.0
     *
     * phpcs:disable Generic.Files.LineLength
     */
    public function __construct($value, Field $field, ?Throwable $cause = null)
    {

        // Define.
        $this->addInfo('context', 'definingValueForField');
        $this->addInfo('field', get_class($field));
        $this->addInfo('fieldName', $field->getName());
        $this->addHint('Given value for Field is inproper. ' . $field->getProperValueHint());

        // Add Model info if reacheable.
        if ($field->hasModel() === true) {
            $this->addInfo('model', get_class($field->getModel()));
            $this->addInfo('modelName', $field->getModel()->getName());
        }

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
