<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Field;

use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Data\FieldInterface;
use Przeslijmi\Shortquery\Data\Model;
use stdClass;

/**
 * Field to use - json.
 */
class JsonField extends Field implements FieldInterface
{

    /**
     * Constructor.
     *
     * @param string  $name    Name of Field.
     * @param boolean $notNull Opt., false. If true - null value is not accepted.
     */
    public function __construct(string $name, bool $notNull = false)
    {

        // Create parent.
        parent::__construct($name, $notNull);

        // Define.
        $this->setType('JsonField');
        $this->setEngineType('json');
        $this->setPhpType('', 'stdClass');
        $this->setPhpDocsType('string|stdClass', 'stdClass');
    }

    /**
     * Checks if value of the Field is valid according to this type.
     *
     * @param null|stdClass $value Value to be checked.
     *
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
     * Convert mixed into stdClass.
     *
     * @param mixed $value Value to be converted to stdClass.
     *
     * @return null|stdClass
     */
    public function setProperType($value) : ?stdClass
    {

        // Short way 1.
        if ($value === null) {
            return null;
        }

        // Short way 2.
        if (is_a($value, 'stdClass') === true) {
            return $value;
        }

        // Short way 3.
        if (is_a(json_decode($value), 'stdClass') === true) {
            return json_decode($value);
        }

        return (object) $value;
    }

    /**
     * Prepare PHP commands to create this Field in model.
     *
     * @return string
     */
    public function toPhp() : string
    {

        // Result.
        $php  = $this->ln(0, '', 1);
        $php .= $this->ln(3, '( new JsonField(\'' . $this->getName() . '\', ' . $this->ex($this->isNotNull()) . ') )');
        $php .= $this->ln(4, '->setPk(' . $this->ex($this->isPrimaryKey()) . ')');
        $php .= $this->ln(2, '', 0);

        return $php;
    }

    /**
     * Prepare PHP commands for getter.
     *
     * @return string
     */
    public function getterToPhp() : string
    {

        $php  = $this->ln(2, '// Convert to JSON object if needed.');
        $php .= $this->ln(2, 'if (is_string(' . $this->cc(true) . ') === true) {');
        $php .= $this->ln(2, '    ' . $this->cc(true) . ' = json_decode(' . $this->cc(true) . ');');
        $php .= $this->ln(2, '}', 2);
        $php .= $this->ln(2, 'return ' . $this->cc(true) . ';');

        return $php;
    }

    /**
     * Prepare PHP commands for comparer given value vs saved value.
     *
     * @return string
     */
    public function compareToPhp() : string
    {

        // Result.
        $php  = $this->ln(0, 'if (');
        $php  = $this->ln(3, $this->cc(true) . ' === $' . $this->cc());
        $php .= $this->ln(3, '|| json_encode(' . $this->cc(true) . ') === json_encode($' . $this->cc() . ')');
        $php .= $this->ln(2, ') {');

        return $php;
    }

    /**
     * Prepare PHP commands for additional, extra methods to put inside generated Field class.
     *
     * @param Model $model To use for PHP code.
     *
     * @return string
     */
    public function extraMethodsToPhp(Model $model) : string
    {

        return '';
    }

    /**
     * Deliver hint for value correctness for this Field.
     *
     * @return string
     */
    public function getProperValueHint() : string
    {

        return 'Has to be stdClass.';
    }
}
