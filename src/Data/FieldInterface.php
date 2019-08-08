<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

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
}
