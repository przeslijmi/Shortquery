<?php

namespace Przeslijmi\Shortquery\Items;

/**
 * Field item - table name and field name.
 */
class Field extends ContentItem
{

    /**
     * Table name for the field.
     *
     * @var   string
     * @since v1.0
     */
    private $table = '';

    /**
     * Field name.
     *
     * @var   string
     * @since v1.0
     */
    private $field = '';

    /**
     * Constructor.
     *
     * @param string $tableAndField Can have both (table & field) or only field.
     *
     * @since  v1.0
     * @return self
     */
    public function __construct(string $tableAndField)
    {

        // Keep table and field name organized.
        if (strpos($tableAndField, '.') === true) {
            list($table, $field) = explode('.', $tableAndField);
        } else {
            $table = '';
            $field = $tableAndField;
        }

        $this->table = $table;
        $this->field = $field;
    }

    /**
     * Getter for table name.
     *
     * @since  v1.0
     * @return string
     */
    public function getTable() : string
    {

        return $this->table;
    }

    /**
     * Getter for field name.
     *
     * @since  v1.0
     * @return string
     */
    public function getField() : string
    {

        return $this->field;
    }
}
