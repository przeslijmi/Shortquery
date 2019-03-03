<?php

namespace Przeslijmi\Shortquery\Items;

class Field extends ContentItem
{

    /**
     * Table name for the field.
     *
     * @var string
     * @since v1.0
     */
    private $table = '';

    /**
     * Field name.
     *
     * @var string
     * @since v1.0
     */
    private $field = '';

    /**
     * Constructor.
     *
     * @param string $tableAndField Can have both (table & field) or only field.
     * @return self
     * @since v1.0
     */
    public function __construct(string $tableAndField)
    {

        // keep table and field name organized
        if (strpos($tableAndField, '.')) {
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
     * @return string
     * @since v1.0
     */
    public function getTable() : string
    {

        return $this->table;
    }

    /**
     * Getter for field name.
     *
     * @return string
     * @since v1.0
     */
    public function getField() : string
    {

        return $this->field;
    }
}
