<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use Przeslijmi\Shortquery\Data\Model;

/**
 * Field in model object.
 */
interface FieldInterface
{

    /**
     * Constructor.
     *
     * @param string  $name    Name of field.
     * @param boolean $notNull Opt., false. If true - null value is not accepted.
     *
     * @since v1.0
     */
    public function __construct(string $name, bool $notNull = false);

    /**
     * Checks if value of the Field is valid according to this type.
     *
     * @param string $value Value to be checked.
     *
     * @since  v1.0
     * @return boolean
     */
    // public function isValueValid($value) : bool;

    /**
     * Prepare PHP commands to create this Field in model.
     *
     * @since  v1.0
     * @return string
     */
    public function toPhp() : string;

    /**
     * Prepare PHP commands for getter.
     *
     * @since  v1.0
     * @return string
     */
    public function getterToPhp() : string;

    /**
     * Prepare PHP commands for comparer given value vs saved value.
     *
     * @since  v1.0
     * @return string
     */
    public function compareToPhp() : string;

    /**
     * Prepare PHP commands for additional, extra methods to put inside generated Field class.
     *
     * @param Model $model To use for PHP code.
     *
     * @since  v1.0
     * @return string
     */
    public function extraMethodsToPhp(Model $model) : string;

    /**
     * Deliver hint for value correctness for this Field.
     *
     * @since  v1.0
     * @return string
     */
    public function getProperValueHint() : string;
}
