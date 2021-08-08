<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Field;

use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Data\FieldInterface;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Exceptions\Items\FieldDictDonoexException;
use Przeslijmi\Shortquery\Exceptions\Items\FieldDictValueDonoexException;
use Przeslijmi\Shortquery\Exceptions\Items\FieldValueInproperException;

/**
 * Field to use - set.
 */
class SetField extends Field implements FieldInterface
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
     */
    public function __construct(string $name, bool $notNull = false)
    {

        // Create parent.
        parent::__construct($name, $notNull);

        // Define.
        $this->setType('SetField');
        $this->setEngineType('set');
        $this->setPhpType('string');
        $this->setPhpDocsType('string');
    }

    /**
     * Setter for `values`.
     *
     * @param string ...$values Values to use in set field.
     *
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
     * @return string[]
     */
    public function getValues() : array
    {

        return $this->values;
    }

    /**
     * Setter for `mainDict`.
     *
     * @param string ...$values Values to use in this dict.
     *
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
     * @param string $dictName  Name of the dictionary.
     * @param string ...$values Values to use in this dict.
     *
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
     * @return string[]
     */
    public function getMainDict() : array
    {

        return $this->getDict('main');
    }

    /**
     * Getter for any dict.
     *
     * @param string $dictName Optional, `main`. Name of dictionary.
     *
     * @throws FieldDictDonoexException When dict with given name has not been found.
     * @return array
     */
    public function getDict(string $dictName = 'main') : array
    {

        // Throw if not found.
        if (isset($this->dicts[$dictName]) === false) {

            // Prepare info.
            $info = [
                get_class($this),
                $this->getName(),
                $dictName,
                implode(', ', array_keys($this->getDicts()))
            ];
            if ($this->hasModel() === true) {
                $info['model']     = get_class($this->getModel());
                $info['modelName'] = $this->getModel()->getName();
            }

            // Throw.
            throw new FieldDictDonoexException($info);
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
     * @param string $keys     Keys to look for (on of sent to `->setValues()`) - comma separated.
     * @param string $dictName Which dictionary.
     *
     * @throws FieldDictValueDonoexException When there is no given key in given dict.
     * @return string
     */
    public function getDictValue(string $keys, string $dictName = 'main') : string
    {

        // Lvd.
        $result = [];

        // Get dict.
        $dict = $this->getDict($dictName);

        // Look for every key.
        foreach (explode(',', $keys) as $key) {

            // Lvd.
            $id = array_search($key, $this->values);

            // Throw.
            if (is_int($id) === false) {

                // Prepare info.
                $info = [
                    get_class($this),
                    $this->getName(),
                    $dictName,
                    $key,
                    implode(', ', $this->getValues($dictName)),
                ];
                if ($this->hasModel() === true) {
                    $info['model']     = get_class($this->getModel());
                    $info['modelName'] = $this->getModel()->getName();
                }

                // Throw.
                throw new FieldDictValueDonoexException($info);
            }

            // Add.
            $result[] = $dict[$id];
        }//end foreach

        return implode(',', $result);
    }

    /**
     * Checks if value of the Field is valid according to this type.
     *
     * @param null|string $value  Value to be checked.
     * @param boolean     $throws Optional, true. If set to false `throw` will be off.
     *
     * @throws FieldValueInproperException If ordered to throw and value is not valid.
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

        // Check values.
        foreach (explode(',', $value) as $oneValue) {
            if (in_array($oneValue, $this->values) === false) {
                $result = false;
                break;
            }
        }

        // Throw.
        if ($result === false && $throws === true) {

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

        return $result;
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
        $php .= $this->ln(3, '( new SetField(\'' . $this->getName() . '\', ' . $this->ex($this->isNotNull()) . ') )');
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
        $php .= $this->ln(2, 'return $this->grabMultiDictFieldValue(' . implode(', ', $gdfvA) . ');', 1);

        return $php;
    }

    /**
     * Prepare PHP commands for comparer given value vs saved value.
     *
     * @return string
     */
    public function compareToPhp() : string
    {

        $php  = $this->ln(0, 'if (');
        $php  = $this->ln(3, 'is_null($' . $this->cc() . ') === is_null(' . $this->cc(true) . ')');
        $php .= $this->ln(
            3,
            '&& count(array_diff((array) $' . $this->cc() . ', (array) ' . $this->cc(true) . ')) === 0'
        );
        $php .= $this->ln(
            3,
            '&& count(array_diff((array) ' . $this->cc(true) . ', (array) $' . $this->cc() . ')) === 0'
        );
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

        // Lvd.
        $pc  = $this->getName('pascalCase');
        $get = $this->getGetterName();
        $set = $this->getSetterName();

        // Result for adder.
        $php  = $this->ln(1, '/**');
        $php .= $this->ln(1, ' * Adds given value to set.');
        $php .= $this->ln(1, ' *');
        $php .= $this->ln(1, ' * @param string $toBeAdded String value to be added.');
        $php .= $this->ln(1, ' *');
        $php .= $this->ln(1, ' * @return ' . $model->getClass('instanceClassName') . '');
        $php .= $this->ln(1, ' */');
        $php .= $this->ln(
            1,
            'public function addTo' . $pc . '(string $toBeAdded) : ' . $model->getClass('instanceClassName')
        );
        $php .= $this->ln(1, '{', 2);
        $php .= $this->ln(2, 'if (empty($this->' . $get . '()) === true) {');
        $php .= $this->ln(3, '$value = explode(\',\', $toBeAdded);');
        $php .= $this->ln(2, '} else {');
        $php .= $this->ln(3, '$value = array_merge(');
        $php .= $this->ln(4, 'explode(\',\', $toBeAdded),');
        $php .= $this->ln(4, 'explode(\',\', $this->' . $get . '())');
        $php .= $this->ln(3, ');');
        $php .= $this->ln(2, '}', 2);
        $php .= $this->ln(2, '$value = array_unique($value);', 2);
        $php .= $this->ln(2, '$value = implode(\',\', $value);', 2);
        $php .= $this->ln(2, 'return $this->' . $set . '(( empty($value) === true ) ? null : $value);', 1);
        $php .= $this->ln(1, '}', 2);

        // Result for deleter.
        $php .= $this->ln(1, '/**');
        $php .= $this->ln(1, ' * Deletes given value from set.');
        $php .= $this->ln(1, ' *');
        $php .= $this->ln(1, ' * @param string $toBeDeleted String value to be deleted.');
        $php .= $this->ln(1, ' *');
        $php .= $this->ln(1, ' * @return ' . $model->getClass('instanceClassName') . '');
        $php .= $this->ln(1, ' */');
        $php .= $this->ln(
            1,
            'public function deleteFrom' . $pc . '(string $toBeDeleted) : ' . $model->getClass('instanceClassName')
        );
        $php .= $this->ln(1, '{', 2);
        $php .= $this->ln(2, '$value = explode(\',\', $this->' . $get . '());', 2);
        $php .= $this->ln(2, 'foreach (explode(\',\', $toBeDeleted) as $toDelete) {', 2);
        $php .= $this->ln(3, '$is = array_search($toDelete, $value);', 2);
        $php .= $this->ln(3, 'if ($is !== false) {');
        $php .= $this->ln(4, 'unset($value[$is]);');
        $php .= $this->ln(3, '}');
        $php .= $this->ln(2, '}', 2);
        $php .= $this->ln(2, '$value = implode(\',\', $value);', 2);
        $php .= $this->ln(2, 'return $this->' . $set . '(( empty($value) === true ) ? null : $value);', 1);
        $php .= $this->ln(1, '}', 2);

        return $php;
    }

    /**
     * Deliver hint for value correctness for this Field.
     *
     * @return string
     */
    public function getProperValueHint() : string
    {

        return 'Only one value from defined is proper: ' . implode(', ', $this->values) . ', or their mix.';
    }
}
