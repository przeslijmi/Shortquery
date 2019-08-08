<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Field;

use DateTime;
use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Data\FieldInterface;

/**
 * Field to use - date.
 */
class DateField extends Field implements FieldInterface
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
        $this->setType('DateField');
        $this->setEngineType('date');
        $this->setPhpType('string');
    }

    /**
     * Checks if value of the Field is valid according to this type.
     *
     * @param null|string $value Value to be checked.
     *
     * @since  v1.0
     * @return boolean
     */
    public function isValueValid(?string $value) : bool
    {

        // If null - it is valid.
        if (is_null($value) === true) {
            return true;
        }

        // Checks.
        // if (in_array($value, $this->values) === false) {
        //     die('EnumField inproper value `' . $value . '` not from possible values, ie.
        // ' . implode(', ', $this->values) . '.');
        // }

        return true;
    }

    public function formatToExcel(string $dateReal) : int
    {

        // Lvd.
        $dateExcel = new DateTime('1900-01-01');
        $dateReal  = new DateTime($dateReal);

        // Calc.
        $days  = $dateExcel->diff($dateReal)->format('%a');
        $days += 2; // add boundary days

        return $days;
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
        $php = '';

        $php .= $this->ln(0, '', 1);
        $php .= $this->ln(3, '(new DateField(\'' . $this->getName() . '\', ' . $this->ex($this->isNotNull()) . '))');
        $php .= $this->ln(4, '->setPk(' . $this->ex($this->isPrimaryKey()) . ')', 2);

        return $php;
    }

    public function getterToPhp() : string
    {

        // Lvd.
        $fieldName = '$this->' . $this->getName('camelCase') . '';
        $toExcel   = '->formatToExcel(' . $fieldName . ');';

        // Lvd.
        $php  = '';
        $php .= $this->ln(2, 'if (' . $fieldName . ' !== null && func_num_args() > 0 && func_get_arg(0) === \'excel\') {');
        $php .= $this->ln(3, 'return (string) $this->grabField(\'' . $this->getName() . '\')' . $toExcel);
        $php .= $this->ln(2, '}', 2);
        $php .= $this->ln(2, 'return $this->' . $this->getName('camelCase') . ';', 1);

        return $php;
    }

    public function compareToPhp() : string
    {

        $php  = '';
        $php .= 'if ($this->' . $this->getName('camelCase') . ' === $' . $this->getName('camelCase') . ') {';

        return $php;
    }

    public function extraMethodsToPhp() : string
    {

        return '';
    }
}
