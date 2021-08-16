<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString;

use Przeslijmi\Shortquery\Items\Field;

/**
 * Converts Field element into string.
 *
 * ## Usage example
 * ```
 * $field = new Field('tableName.fieldName');
 * echo (new FieldToString($field))->toString(); // will return `tableName`.`fieldName`
 * ```
 */
class FieldToString
{

    /**
     * Field element to be converted to string.
     *
     * @var Field
     */
    private $field;

    /**
     * Context name - where are you going to use result of this `FieldToString` class?
     *
     * @var string
     */
    private $context;

    /**
     * Constructor.
     *
     * @param Field  $field   Field element to be converted to string.
     * @param string $context Name of context.
     */
    public function __construct(Field $field, string $context = '')
    {

        $this->field   = $field;
        $this->context = $context;
    }

    /**
     * Converts to string.
     *
     * @return string
     */
    public function toString() : string
    {

        $result = '';

        if (empty($this->field->getTable()) === false) {
            $result .= '`' . $this->field->getTable() . '`.';
        }

        if ($this->field->getField() === '*') {
            $result .= '*';
        } else {
            $result .= '`' . $this->field->getField() . '`';
        }

        if (empty($this->field->getAlias()) === false) {
            $result .= ' AS `' . $this->field->getAlias() . '`';
        }

        return $result;
    }
}
