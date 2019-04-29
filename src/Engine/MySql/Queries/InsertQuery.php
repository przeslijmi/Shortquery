<?php

namespace Przeslijmi\Shortquery\Engine\MySql\Queries;

use Przeslijmi\Shortquery\Engine\MySql;

/**
 * Tool for creating INSERT query (including its string representation).
 */
class InsertQuery
{

    /**
     * Link to Engine object.
     *
     * @var Engine.
     */
    private $engine;

    /**
     * Link to Collection object.
     *
     * @var Collection
     */
    private $collection;

    /**
     * Constructor.
     *
     * @param MySql $engine Engine.
     *
     * @since v1.0
     */
    public function __construct(MySql $engine)
    {

        $this->engine     = $engine;
        $this->collection = $engine->getCollection();
    }

    /**
     * Converts INSERT query into string.
     *
     * @since  v1.0
     * @return string
     */
    public function toString()
    {

        $setsToAdd = [];

        $fieldsGettersNames = $this->collection->getModel()->getFieldsGettersNames();

        foreach ($this->collection->getObjects() as $object) {

            $fieldsToAdd = [];

            foreach ($fieldsGettersNames as $getterName) {

                $value = $object->$getterName();

                if (is_null($value) === true) {
                    $fieldsToAdd[] = 'NULL';
                } elseif (is_string($value) === true) {
                    $fieldsToAdd[] = "'" . addslashes($value) . "'";
                } elseif (is_bool($value) === true) {
                    $fieldsToAdd[] = (int) $value;
                } elseif (is_scalar($value) === true) {
                    $fieldsToAdd[] = str_replace(',', '.', $value);
                } else {
                    // @todo make throw instead of this
                    die('jdfgoijaf3498afjw9qjg54');
                }
            }

            $setsToAdd[] = '(' . implode(', ', $fieldsToAdd) . ')';
        }//end foreach

        $query  = 'INSERT INTO ';
        $query .= $this->collection->getModel()->getName();
        $query .= ' VALUES ';
        $query .= implode(', ', $setsToAdd) . ';';

        return $query;
    }
}
