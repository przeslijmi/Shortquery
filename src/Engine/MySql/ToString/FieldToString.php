<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\Mysql\ToString;

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
     * @var   Field
     * @since v1.0
     */
    private $field;

    /**
     * Context name - where are you going to use result of this `FieldToString` class?
     *
     * @var   string
     * @since v1.0
     */
    private $context;

    /**
     * Constructor.
     *
     * @param Field  $field   Field element to be converted to string.
     * @param string $context Name of context.
     *
     * @since v1.0
     */
    public function __construct(Field $field, string $context = '')
    {

        $this->field   = $field;
        $this->context = $context;
    }

    /**
     * Converts to string.
     *
     * @since  v1.0
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

        return $result;
    }
}
