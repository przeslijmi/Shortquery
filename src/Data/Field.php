<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Data\FieldEchoingMethods;

/**
 * Field in Model object.
 */
abstract class Field extends FieldEchoingMethods
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
     * PHP type of value on input (string, int, etc.).
     *
     * @var string
     */
    private $phpTypeInput;

    /**
     * PHP type of value on output (string, int, etc.).
     *
     * @var string
     */
    private $phpTypeOutput;

    /**
     * PHP DOCS type of value on input (string, int, etc.).
     *
     * @var string
     */
    private $phpDocsTypeInput;

    /**
     * PHP DOCS type of value on output (string, int, etc.).
     *
     * @var string
     */
    private $phpDocsTypeOutput;

    /**
     * Constructor.
     *
     * @param string  $name    Name of field.
     * @param boolean $notNull Opt., false. If true - null value is not accepted.
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
     * @return self
     */
    private function setName(string $name) : self
    {

        $this->name = $name;

        return $this;
    }

    /**
     * Getter for `name`.
     *
     * @param string $case Optional 'original'. Otherwise `camelCase` or `pascalCase` can be sent.
     *
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
     * @return self
     */
    public function setModel(Model $model) : self
    {

        $this->model = $model;

        return $this;
    }

    /**
     * Checks if Field has model.
     *
     * @return boolean
     */
    public function hasModel() : bool
    {

        return ( $this->model !== null );
    }

    /**
     * Getter for Model instance in which Field is used.
     *
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

        return $this;
    }

    /**
     * Setter alias of 'setPrimaryKey'.
     *
     * @param boolean $isPrimaryKey Is this is a primary key.
     *
     * @return self
     */
    public function setPk(bool $isPrimaryKey) : self
    {

        return $this->setPrimaryKey($isPrimaryKey);
    }

    /**
     * Getter for `isPrimaryKey` setting.
     *
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
     * @return self
     */
    private function setNotNull(bool $isNotNull) : self
    {

        $this->isNotNull = $isNotNull;

        return $this;
    }

    /**
     * Getter for `isNotNull` setting.
     *
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
     * @return self
     */
    protected function setType(string $type) : self
    {

        $this->type = $type;

        return $this;
    }

    /**
     * Getter for `type`.
     *
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
     * @return string
     */
    public function getEngineType() : string
    {

        return $this->engineType;
    }

    /**
     * Setter for `phpTypeInput` and `phpTypeOutput`.
     *
     * @param string $phpTypeInput  PHP type of value on input (string, int, etc.).
     * @param string $phpTypeOutput Optional, identical. PHP type of value on output (string, int, etc.).
     *
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
     * Setter for `phpDocsTypeInput` and `phpDocsTypeOutput`.
     *
     * @param string $phpDocsTypeInput  PHP DOCS type of value on input (string, int, etc.).
     * @param string $phpDocsTypeOutput Optional, identical. PHP DOCS type of value on output (string, int, etc.).
     *
     * @return self
     */
    public function setPhpDocsType(string $phpDocsTypeInput, ?string $phpDocsTypeOutput = null) : self
    {

        if (is_null($phpDocsTypeOutput) === true) {
            $phpDocsTypeOutput = $phpDocsTypeInput;
        }

        $this->phpDocsTypeInput  = $phpDocsTypeInput;
        $this->phpDocsTypeOutput = $phpDocsTypeOutput;

        return $this;
    }

    /**
     * Getter for `phpTypeInput`.
     *
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
     * Convert php type input into braced php type input (eg. `string` into `(string)`).
     *
     * @return string
     */
    public function getPhpTypeInputInBraces() : string
    {

        // If php type input is not defined - return empty string.
        if (empty($this->phpTypeInput) === true) {
            return '';
        }

        return '(' . $this->phpTypeInput . ') ';
    }

    /**
     * Getter for `phpDocsTypeOutput`.
     *
     * @return string
     */
    public function getPhpDocsTypeInput() : string
    {

        $result  = ( ( $this->isNotNull() === false ) ? 'null|' : '' );
        $result .= $this->phpDocsTypeInput;

        return $result;
    }

    /**
     * Getter for `phpTypeOutput`.
     *
     * @return string
     */
    public function getPhpTypeOutput() : string
    {

        // Create result.
        $result  = ( ( $this->isNotNull() === false ) ? '?' : '' );
        $result .= $this->phpTypeOutput;

        return $result;
    }

    /**
     * Getter for `phpDocsTypeOutput`.
     *
     * @return string
     */
    public function getPhpDocsTypeOutput() : string
    {

        // Create result.
        $result  = ( ( $this->isNotNull() === false ) ? 'null|' : '' );
        $result .= $this->phpDocsTypeOutput;

        return $result;
    }

    /**
     * Getter for name of a getter method that returns value of this field.
     *
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
}
