<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Field;

use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Data\FieldInterface;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Exceptions\Items\FieldDictDonoexException;
use Przeslijmi\Shortquery\Exceptions\Items\FieldDictValueDonoexException;
use Przeslijmi\Shortquery\Exceptions\Items\FieldValueInproperException;

/**
 * Field to use - enum.
 */
class EnumField extends Field implements FieldInterface
{

    /**
     * Possible values in the Field.
     *
     * @var string[]
     */
    private $values = [];

    /**
     * Dictionaries for the Field.
     *
     * @var string[][]
     */
    private $dicts = [
        'main' => [],
    ];

    /**
     * Constructor.
     *
     * @param string  $name    Name of Field.
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
        $this->setPhpDocsType('string');
    }

    /**
     * Setter for `values`.
     *
     * @param string[] $values Values to use in set field.
     *
     * @since  v1.0
     * @return self
     */
    public function setValues(string ...$values) : self
    {

        // Save.
        $this->values = $values;
        $this->setMainDict(...$values);

        return $this;
    }

    /**
     * Getter for `values`.
     *
     * @since  v1.0
     * @return string[]
     */
    public function getValues() : array
    {

        return $this->values;
    }

    /**
     * Setter for `mainDict`.
     *
     * @param string[] $values Values to use in this dict.
     *
     * @since  v1.0
     * @return self
     */
    public function setMainDict(string ...$values) : self
    {

        // Save.
        $this->setDict('main', ...$values);

        return $this;
    }

    /**
     * Setter for any dict from `dicts`.
     *
     * @param string   $dictName Name of the dictionary
     * @param string[] $values   Values to use in this dict.
     *
     * @since  v1.0
     * @return self
     */
    public function setDict(string $dictName, string ...$values) : self
    {

        // Save.
        $this->dicts[$dictName] = $values;

        return $this;
    }

    /**
     * Getter for `mainDict`.
     *
     * @since  v1.0
     * @return string[]
     */
    public function getMainDict() : array
    {

        return $this->getDict('main');
    }

    /**
     * Getter for any dict.
     *
     * @since  v1.0
     * @throws FieldDictDonoexException When dict with given name has not been found.
     * @return array
     */
    public function getDict(string $dictName = 'main') : array
    {

        // Throw if not found.
        if (isset($this->dicts[$dictName]) === false) {
            throw new FieldDictDonoexException($dictName, $this);
        }

        return $this->dicts[$dictName];
    }

    /**
     * Getter for all dicts.
     *
     * @return string[][]
     */
    public function getDicts() : array
    {

        return $this->dicts;
    }

    /**
     * Getter for given key from given dict.
     *
     * @param string $key      Key to look for (on of sent to `->setValues()`).
     * @param string $dictName Which dictionary.
     *
     * @since  v1.0
     * @throws FieldDictValueDonoexException When there is no given key in given dict.
     * @return string
     */
    public function getDictValue(string $key, string $dictName = 'main') : string
    {

        // Get dict.
        $dict = $this->getDict($dictName);

        // Lvd.
        $id = array_search($key, $this->values);

        // Throw.
        if (is_int($id) === false) {
            throw new FieldDictValueDonoexException($dictName, $key, $this);
        }

        return $dict[$id];
    }

    /**
     * Checks if value of the Field is valid according to this type.
     *
     * @param null|string $value  Value to be checked.
     * @param boolean     $throws Optional, true. If set to false `throw` will be off.
     *
     * @since  v1.0
     * @return boolean
     */
    public function isValueValid(?string $value, bool $throws = true) : bool
    {

        // Lvd.
        $result = true;

        // If null - it is valid.
        if (is_null($value) === true) {
            return $result;
        }

        // Checks.
        if (in_array($value, $this->values) === false) {
            $result = false;
        }

        // Throw.
        if ($result === false && $throws === true) {
            throw new FieldValueInproperException($value, $this);
        }

        return $result;
    }

    /**
     * Prepare PHP commands to create this Field in model.
     *
     * @since  v1.0
     * @return string
     */
    public function toPhp() : string
    {

        // Result.
        $php  = $this->ln(0, '', 1);
        $php .= $this->ln(3, '( new EnumField(\'' . $this->getName() . '\', ' . $this->ex($this->isNotNull()) . ') )');
        $php .= $this->ln(4, '->setValues(' . $this->imp($this->getValues()) . ')');

        // Add all dicts.
        foreach ($this->getDicts() as $dictName => $values) {
            $php .= $this->ln(4, '->setDict(\'' . $dictName . '\', ' . $this->imp($values) . ')');
        }

        // Finish.
        $php .= $this->ln(4, '->setPk(' . $this->ex($this->isPrimaryKey()) . ')', 1);
        $php .= $this->ln(2, '', 0);

        return $php;
    }

    /**
     * Prepare PHP commands for getter.
     *
     * @since  v1.0
     * @return string
     */
    public function getterToPhp() : string
    {

        // Lvd.
        $gdfvA   = [];
        $gdfvA[] = '\'' . $this->getName() . '\'';
        $gdfvA[] = '( func_get_args()[0] ?? \'main\' )';
        $gdfvA[] = $this->cc(true);

        // Result.
        $php  = $this->ln(2, 'if (func_num_args() === 0) {');
        $php .= $this->ln(3, 'return ' . $this->cc(true) . ';');
        $php .= $this->ln(2, '}', 2);
        $php .= $this->ln(2, 'return $this->grabDictFieldValue(' . implode(', ', $gdfvA) . ');', 1);

        return $php;
    }

    /**
     * Prepare PHP commands for comparer given value vs saved value.
     *
     * @since  v1.0
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
     * @since  v1.0
     * @return string
     */
    public function extraMethodsToPhp(Model $model) : string
    {

        return '';
    }

    /**
     * Deliver hint for value correctness for this Field.
     *
     * @since  v1.0
     * @return string
     */
    public function getProperValueHint() : string
    {

        return 'Only one value from defined is proper: ' . implode(', ', $this->values);
    }
}