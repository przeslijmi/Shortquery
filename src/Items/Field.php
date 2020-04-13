<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Items;

use Przeslijmi\Shortquery\Items\ContentItem;

/**
 * Field item - table name and field name.
 */
class Field extends ContentItem
{

    /**
     * Table name for this Field.
     *
     * @var string
     */
    private $table = '';

    /**
     * Field name.
     *
     * @var string
     */
    private $field = '';

    /**
     * Factory method.
     *
     * @param string $fieldOrTableWithField Field name or field and table name separated with dot.
     *
     * @todo Getting rid of gravises is rather harsh. Should be refactored.
     *
     * @return Field
     */
    public static function factory(string $fieldOrTableWithField) : Field
    {

        // Delete gravis.
        $fieldOrTableWithField = trim($fieldOrTableWithField, '`');
        $fieldOrTableWithField = str_replace('`.`', '.', $fieldOrTableWithField);

        // Keep table and field name organized.
        if (strpos($fieldOrTableWithField, '.') !== false) {
            list($table, $field) = explode('.', $fieldOrTableWithField);
        } else {
            $table = '';
            $field = $fieldOrTableWithField;
        }

        return new self($field, $table);
    }

    /**
     * Constructor.
     *
     * @param string $field Field name.
     * @param string $table Optional, empty. Table name.
     *
     * @return self
     */
    public function __construct(string $field, string $table = '')
    {

        $this->setTable($table);
        $this->setField($field);
    }

    /**
     * Setter for table.
     *
     * @param string $table Table name for this Field.
     *
     * @return self
     */
    public function setTable(string $table) : self
    {

        $this->table = $table;

        return $this;
    }

    /**
     * Getter for table name.
     *
     * @return string
     */
    public function getTable() : string
    {

        return $this->table;
    }

    /**
     * Setter for field.
     *
     * @param string $field Field name.
     *
     * @return self
     */
    public function setField(string $field) : self
    {

        $this->field = $field;

        return $this;
    }

    /**
     * Getter for field name.
     *
     * @return string
     */
    public function getField() : string
    {

        return $this->field;
    }
}
