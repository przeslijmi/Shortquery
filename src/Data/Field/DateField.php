<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Field;

use DateTime;
use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Data\FieldInterface;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Exceptions\Items\FieldValueInproperException;

/**
 * Field to use - date.
 */
class DateField extends Field implements FieldInterface
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
        $this->setType('DateField');
        $this->setEngineType('date');
        $this->setPhpType('string');
        $this->setPhpDocsType('string');
    }

    /**
     * Checks if value of the Field is valid according to this type.
     *
     * @param null|string $value  Value to be checked.
     * @param boolean     $throws Optional, true. If set to false `throw` will be off.
     *
     * @throws FieldValueInproperException When Field value is inproper.
     * @return boolean
     */
    public function isValueValid(?string $value, bool $throws = true) : bool
    {

        // If null - it is valid.
        if (is_null($value) === true) {
            return true;
        }

        // Prepare to check.
        $ex       = explode('-', $value);
        $ex[0]    = (int) ( $ex[0] ?? 0 );
        $ex[1]    = (int) ( $ex[1] ?? 0 );
        $ex[2]    = (int) ( $ex[2] ?? 0 );
        $testDate = date('Y-m-d', mktime(0, 0, 0, $ex[1], $ex[2], $ex[0]));

        // Check and return true if this statement is true.
        if ($testDate === $value) {
            return true;
        }

        // Throws.
        if ($throws === true) {

            // Prepare info.
            $info = [
                $this->getProperValueHint(),
                get_class($this),
                $this->getName(),
                (string) $value,
            ];
            if ($this->hasModel() === true) {
                $info['model']     = get_class($this->getModel());
                $info['modelName'] = $this->getModel()->getName();
            }

            // Throw.
            throw new FieldValueInproperException($info);
        }

        return false;
    }

    /**
     * Converts date into Excel format integer (number of days since 1900-01-01).
     *
     * @param string $dateReal Date to convert.
     *
     * @return integer
     */
    public function formatToExcel(string $dateReal) : int
    {

        // Test.
        $this->isValueValid($dateReal);

        // Lvd.
        $dateExcel = new DateTime('1900-01-01');
        $dateReal  = new DateTime($dateReal);

        // Calc.
        $days = $dateExcel->diff($dateReal)->format('%a');

        // Add boundary days.
        $days += 2;

        return $days;
    }

    /**
     * Prepare PHP commands to create this Field in model.
     *
     * @return string
     */
    public function toPhp() : string
    {

        // Create.
        $php  = $this->ln(0, '', 1);
        $php .= $this->ln(3, '( new DateField(\'' . $this->getName() . '\', ' . $this->ex($this->isNotNull()) . ') )');
        $php .= $this->ln(4, '->setPk(' . $this->ex($this->isPrimaryKey()) . ')', 1);
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

        // Lvd.
        $php  = $this->ln(2, 'if (empty(' . $this->cc(true) . ') === true) {');
        $php .= $this->ln(3, $this->cc(true) . ' = null;');
        $php .= $this->ln(2, '}', 2);
        $php .= $this->ln(
            2,
            'if (' . $this->cc(true) . ' !== null && func_num_args() > 0 && func_get_arg(0) === \'excel\') {'
        );
        $php .= $this->ln(3, 'return (string) $this->grabField(\'' . $this->getName() . '\')');
        $php .= $this->ln(4, '->formatToExcel(' . $this->cc(true) . ');');
        $php .= $this->ln(2, '}', 2);
        $php .= $this->ln(2, 'return ' . $this->cc(true) . ';', 1);

        return $php;
    }

    /**
     * Prepare PHP commands for comparer given value vs saved value.
     *
     * @return string
     */
    public function compareToPhp() : string
    {

        return $this->ln(0, 'if (' . $this->cc(true) . ' === $' . $this->cc() . ') {');
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

        return 'Only YYYY-MM-DD format is accepted.';
    }
}
