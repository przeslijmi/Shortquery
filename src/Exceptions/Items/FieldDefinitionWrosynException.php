<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

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
     * @param string $error What is the error (goes to hint).
     * @param Field  $field Field that have a problem.
     */
    public function __construct(string $error, Field $field)
    {

        // Define.
        $this->addInfo('context', 'definingFieldType');
        $this->addInfo('field', get_class($field));
        $this->addInfo('fieldName', $field->getName());
        $this->addHint('You\'re trying to create Field with wrong type. ' . $error);
    }
}
