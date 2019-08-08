<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Field;

use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Data\FieldInterface;

/**
 * Field to use - enum.
 */
class EnumField extends Field implements FieldInterface
{

    /**
     * Possible values in the Field.
     *
     * @var array
     */
    private $values = [];

    /**
     * Dictionaries for the Field.
     *
     * @var array
     */
    private $dicts = [
        'main' => [],
    ];

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
        $this->setType('EnumField');
        $this->setEngineType('enum');
        $this->setPhpType('string');
    }

    /**
     * Setter for `values`.
     *
     * @since  v1.0
     * @return self
     */
    public function setValues() : self
    {

        // Save.
        $this->values = func_get_args();
        $this->setMainDict(...func_get_args());

        return $this;
    }

    /**
     * Getter for `values`.
     *
     * @return array
     */
    public function getValues() : array
    {

        return $this->values;
    }

    public function getDictValue(string $key, string $dictName = 'main') : string
    {

        // Lvd.
        $id = array_search($key, $this->values);

        return $this->dicts[$dictName][$id];
    }

    /**
     * Setter for `mainDict`.
     *
     * @since  v1.0
     * @return self
     */
    public function setMainDict() : self
    {

        // Save.
        $this->dicts['main'] = func_get_args();

        return $this;
    }

    /**
     * Getter for `mainDict`.
     *
     * @return array
     */
    public function getMainDict() : array
    {

        return $this->dicts['main'];
    }

    /**
     * Setter for `mainDict`.
     *
     * @since  v1.0
     * @return self
     */
    public function setDict() : self
    {

        // Lvd.
        $dictName = func_get_arg(0);
        $values   = array_slice(func_get_args(), 1);

        // Save.
        $this->dicts[$dictName] = $values;

        return $this;
    }

    /**
     * Getter for any dict.
     *
     * @return array
     */
    public function getDict(string $dictName = 'main') : array
    {

        return $this->dicts[$dictName];
    }

    /**
     * Getter for any dict.
     *
     * @return array
     */
    public function getDicts() : array
    {

        return $this->dicts;
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
        if (in_array($value, $this->values) === false) {
            $hint  = 'EnumField inproper value `' . $value . '` not from possible values, ';
            $hint .= 'ie. ' . implode(', ', $this->values) . '.';
            die($hint);

        }

        return true;
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
        $php .= $this->ln(3, '(new EnumField(\'' . $this->getName() . '\', ' . $this->ex($this->isNotNull()) . '))');
        $php .= $this->ln(4, '->setValues(' . $this->csv($this->getValues()) . ')');

        foreach ($this->getDicts() as $dictName => $values) {
            $php .= $this->ln(4, '->setDict(\'' . $dictName . '\', ' . $this->csv($values) . ')');
        }

        $php .= $this->ln(4, '->setPk(' . $this->ex($this->isPrimaryKey()) . ')', 2);

        return $php;
    }

    public function getterToPhp() : string
    {

        // Lvd.
        $gdfvA   = [];
        $gdfvA[] = '\'' . $this->getName() . '\'';
        $gdfvA[] = '( func_get_args()[0] ?? \'main\' )';
        $gdfvA[] = '$this->' . $this->getName('camelCase') . '';

        // Lvd.
        $php  = '';
        $php .= $this->ln(2, 'if (func_num_args() === 0) {');
        $php .= $this->ln(3, 'return $this->' . $this->getName('camelCase') . ';');
        $php .= $this->ln(2, '}', 2);
        $php .= $this->ln(2, 'return $this->grabDictFieldValue(' . implode(', ', $gdfvA) . ');', 1);

        return $php;
    }

    public function compareToPhp() : string
    {

        $php  = '';
        $php .= $this->ln(0, 'if ($this->' . $this->getName('camelCase') . ' === $' . $this->getName('camelCase') . ') {');

        return $php;
    }

    public function extraMethodsToPhp() : string
    {

        return '';
    }
}
