<?php

namespace Przeslijmi\Shortquery\Data;

/**
 * Field in model object.
 */
class Field
{

    /**
     * Name of field.
     *
     * @var string
     */
    private $name;

    /**
     * Type of value of field.
     *
     * @var string
     */
    private $type;

    /**
     * Is this is a primary key.
     *
     * @var boolean
     */
    private $isPrimaryKey = false;

    /**
     * Constructor.
     *
     * @param string $name Name of field.
     * @param string $type Type of value of field.
     *
     * @since v1.0
     */
    public function __construct(string $name, string $type)
    {

        $this->name = $name;
        $this->type = $type;
    }

    /**
     * Setter for primary key setting.
     *
     * @param boolean $isPrimaryKey Is this is a primary key.
     *
     * @since  v1.0
     * @return self
     */
    public function setPrimaryKey(bool $isPrimaryKey = true) : self
    {

        $this->isPrimaryKey = $isPrimaryKey;

        return $this;
    }

    /**
     * Getter for primary key setting.
     *
     * @since  v1.0
     * @return boolean
     */
    public function isPrimaryKey() : bool
    {

        return $this->isPrimaryKey;
    }

    /**
     * Getter for fields name.
     *
     * @since  v1.0
     * @return string
     */
    public function getName() : string
    {

        return $this->name;
    }

    /**
     * Getter for name of a getter method returning value of this field.
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
     * Getter for name of a setter method returning value of this field.
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
}
