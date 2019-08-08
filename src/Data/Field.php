<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use Przeslijmi\Shortquery\Data\Model;

/**
 * Field in Model object.
 */
abstract class Field
{

    /**
     * Name of Field.
     *
     * @var string
     */
    private $name;

    /**
     * Model instance in which Field is used.
     *
     * @var Model
     */
    private $model;

    /**
     * Is this is a primary key.
     *
     * @var boolean
     */
    private $isPrimaryKey = false;

    /**
     * Is this is a not null field.
     *
     * @var boolean
     */
    private $isNotNull = false;

    /**
     * Type of field.
     *
     * @var string
     */
    private $type;

    /**
     * Engine type of field.
     *
     * @var string
     */
    private $engineType;

    /**
     * PHP type of value (string, int, etc.).
     *
     * @var string
     */
    private $phpTypeInput;

    /**
     *
     */
    private $phpTypeOutput;

    /**
     * Constructor.
     *
     * @param string  $name    Name of field.
     * @param boolean $notNull Opt., false. If true - null value is not accepted.
     *
     * @since v1.0
     */
    public function __construct(string $name, bool $notNull = false)
    {

        $this->setName($name);
        $this->setNotNull($notNull);
    }

    /**
     * Setter for `name`.
     *
     * @param string $name Name of field.
     *
     * @since  v1.0
     * @return self
     */
    public function setName(string $name) : self
    {

        $this->name = $name;

        return $this;
    }

    /**
     * Getter for `name`.
     *
     * @param string $case Optional 'original'. Otherwise `camelCase` or `pascalCase` can be sent.
     *
     * @since  v1.0
     * @return string
     */
    public function getName(string $case = 'original') : string
    {

        // If this is camel or pascal case.
        if ($case === 'pascalCase' || $case === 'camelCase') {

            // Lvd.
            $name = explode('_', $this->name);

            // Change case of every word.
            foreach ($name as $i => $word) {
                $name[$i] = ucfirst($word);
            }

            // Lvd.
            $name = implode('', $name);

            if ($case === 'pascalCase') {
                return $name;
            } else {
                return lcfirst($name);
            }
        }

        return $this->name;
    }

    /**
     * Setter for Model instance in which Field is used.
     *
     * @param Model $model Model instance in which Field is used.
     *
     * @since  v1.0
     * @return self
     */
    public function setModel(Model $model) : self
    {

        $this->model = $model;

        return $this;
    }

    /**
     * Getter for Model instance in which Field is used.
     *
     * @since  v1.0
     * @return Model
     */
    public function getModel() : Model
    {

        return $this->model;
    }

    /**
     * Setter for `isPrimaryKey`.
     *
     * @param boolean $isPrimaryKey Is this is a primary key.
     *
     * @since  v1.0
     * @return self
     */
    public function setPrimaryKey(bool $isPrimaryKey) : self
    {

        // Save.
        $this->isPrimaryKey = $isPrimaryKey;

        // If this is PK - it is also NOT NULL.
        if ($this->isPrimaryKey === true) {
            $this->setNotNull(true);
        }

        // Check.
        if (is_null($this->model) === false) {
            $this->getModel()->validate();
        }

        return $this;
    }

    /**
     * Setter alias of 'setPrimaryKey'.
     *
     * @param boolean $isPrimaryKey Is this is a primary key.
     *
     * @since  v1.0
     * @return self
     */
    public function setPk(bool $isPrimaryKey) : self
    {

        return $this->setPrimaryKey($isPrimaryKey);
    }

    /**
     * Getter for `isPrimaryKey` setting.
     *
     * @since  v1.0
     * @return boolean
     */
    public function isPrimaryKey() : bool
    {

        return $this->isPrimaryKey;
    }

    /**
     * Setter for `isNotNull`.
     *
     * @param boolean $isNotNull Is this is a not null field.
     *
     * @since  v1.0
     * @return self
     */
    public function setNotNull(bool $isNotNull) : self
    {

        $this->isNotNull = $isNotNull;

        return $this;
    }

    /**
     * Getter for `isNotNull` setting.
     *
     * @since  v1.0
     * @return boolean
     */
    public function isNotNull() : bool
    {

        return $this->isNotNull;
    }

    /**
     * Setter for `type`.
     *
     * @param string $type Type of field.
     *
     * @since  v1.0
     * @return self
     */
    public function setType(string $type) : self
    {

        $this->type = $type;

        return $this;
    }

    /**
     * Getter for `type`.
     *
     * @since  v1.0
     * @return string
     */
    public function getType() : string
    {

        return $this->type;
    }

    /**
     * Setter for `engineType`.
     *
     * @param string $engineType Engine type of field.
     *
     * @since  v1.0
     * @return self
     */
    public function setEngineType(string $engineType) : self
    {

        $this->engineType = $engineType;

        return $this;
    }

    /**
     * Getter for `engineType`.
     *
     * @since  v1.0
     * @return string
     */
    public function getEngineType() : string
    {

        return $this->engineType;
    }

    /**
     * Setter for `phpTypeInput` and `phpTypeOutput`.
     *
     * @param string $phpType PHP type of value (string, int, etc.).
     *
     * @since  v1.0
     * @return self
     */
    public function setPhpType(string $phpTypeInput, ?string $phpTypeOutput = null) : self
    {

        if (is_null($phpTypeOutput) === true) {
            $phpTypeOutput = $phpTypeInput;
        }

        $this->phpTypeInput  = $phpTypeInput;
        $this->phpTypeOutput = $phpTypeOutput;

        return $this;
    }

    /**
     * Getter for `phpTypeInput`.
     *
     * @since  v1.0
     * @return string
     */
    public function getPhpTypeInput() : string
    {

        if (empty($this->phpTypeInput) === true) {
            return '';
        }

        $result  = ( ( $this->isNotNull() === false ) ? '?' : '' );
        $result .= $this->phpTypeInput . ' ';

        return $result;
    }

    /**
     * Getter for `phpTypeOutput`.
     *
     * @since  v1.0
     * @return string
     */
    public function getPhpTypeOutput() : string
    {

        if (empty($this->phpTypeOutput) === true) {
            return '';
        }

        $result  = ( ( $this->isNotNull() === false ) ? '?' : '' );
        $result .= $this->phpTypeOutput . ' ';

        return $result;
    }

    /**
     * Getter for name of a getter method that returns value of this field.
     *
     * @since  v1.0
     * @return string
     */
    public function getGetterName() : string
    {

        $nameExploded = explode('_', $this->name);

        array_walk(
            $nameExploded,
            function (&$value) {
                $value = ucfirst($value);
            }
        );

        return 'get' . implode('', $nameExploded);
    }

    /**
     * Getter for name of a setter method that sets value for this field.
     *
     * @since  v1.0
     * @return string
     */
    public function getSetterName() : string
    {

        $nameExploded = explode('_', $this->name);

        array_walk(
            $nameExploded,
            function (&$value) {
                $value = ucfirst($value);
            }
        );

        return 'set' . implode('', $nameExploded);
    }

    protected function ind(int $repeatments) : string
    {

        // Lvd.
        $indent = '    ';

        return str_repeat($indent, $repeatments);
    }

    protected function ln(int $repeatments, string $lineOfCode, int $newLines = 1) : string
    {

        return $this->ind($repeatments) . $lineOfCode . str_repeat(PHP_EOL, $newLines);
    }

    protected function ex($variable) : string
    {

        return var_export($variable, true);
    }

    protected function imp(array $array, string $start = '\'', string $end = '\'', string $middle = ', ') : string
    {

        // Lvd.
        $separator = $end . $middle . $start;

        // Add enclosers.
        foreach ($array as $i => $element) {
            $array[$i] = $start . str_replace($end, '\\' . $end, $element) . $end;
        }

        return implode($middle, $array);
    }

    protected function csv(array $array) : string
    {

        return $this->imp($array, '\'', '\'', ',');
    }
}
