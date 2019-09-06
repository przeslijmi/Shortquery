<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Shortquery\Data\Field;

/**
 * Definition of type for given Field is not proper.
 */
class FieldDefinitionWrosynException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string         $error What is the error (goes to hint).
     * @param Field          $field Field that have a problem.
     * @param Throwable|null $cause Throwable that caused the problem.
     *
     * @since v1.0
     *
     * phpcs:disable Generic.Files.LineLength
     */
    public function __construct(string $error, Field $field, ?Throwable $cause = null)
    {

        // Define.
        $this->addInfo('context', 'definingFieldType');
        $this->addInfo('field', get_class($field));
        $this->addInfo('fieldName', $field->getName());
        $this->addHint('You\'re trying to create Field with wrong type. ' . $error);

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
