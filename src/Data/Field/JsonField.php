<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Field;

use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Data\FieldInterface;
use stdClass;

/**
 * Field to use - json.
 */
class JsonField extends Field implements FieldInterface
{

    /**
     * Constructor.
     *
     * @param string $name Name of Field.
     * @param boolean $notNull Opt., false. If true - null value is not accepted.
     *
     * @since v1.0
     */
    public function __construct(string $name, bool $notNull = false)
    {

        // Create parent.
        parent::__construct($name, $notNull);

        // Define.
        $this->setType('JsonField');
        $this->setEngineType('json');
        $this->setPhpType('', 'stdClass');
    }

    /**
     * Checks if value of the Field is valid according to this type.
     *
     * @param string $value Value to be checked.
     *
     * @since  v1.0
     * @return boolean
     */
    public function isValueValid(?stdClass $value) : bool
    {

        // If null - it is valid.
        if (is_null($value) === true) {
            return true;
        }

        return true;
    }

    /**
     *
     *
     * @param string $value Value to be checked.
     *
     * @since  v1.0
     * @return boolean
     */
    public function setProperType($value) : ?stdClass
    {

        if (is_a($value, 'stdClass') === true) {
            return $value;
        }

        if ($value === null) {
            return null;
        }

        return json_decode($value);
    }

    /**
     * Prepare PHP commands to create this Field in model.
     *
     * @since  v1.0
     * @return string
     */
    public function toPhp() : string
    {

        // Lvd.
        $indent = '    ';

        // Result.
        $result  = PHP_EOL;
        $result .= str_repeat($indent, 3) . '(new JsonField(\'' . $this->getName() . '\', ';
        $result .= var_export($this->isNotNull(), true) . '))' . PHP_EOL;
        $result .= str_repeat($indent, 4) . '->setPk(' . var_export($this->isPrimaryKey(), true) . ')' . PHP_EOL;
        $result .= str_repeat($indent, 2);

        return $result;
    }

    public function getterToPhp() : string
    {

        return $this->ln(2, 'return $this->' . $this->getName('camelCase') . ';');
    }

    public function compareToPhp() : string
    {

        $php  = '';
        $php .= 'if ($this->' . $this->getName('camelCase') . ' == $' . $this->getName('camelCase') . ') {';

        return $php;
    }

    public function extraMethodsToPhp() : string
    {

        return '';
    }
}
