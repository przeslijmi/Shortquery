<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\Queries;

use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Engine\MySql;
use Przeslijmi\Shortquery\Engine\MySql\Query;

/**
 * Tool for creating UPDATE query (including its string representation).
 */
class UpdateQuery extends Query
{

    /**
     * Converts UPDATE query into string.
     *
     * @return string
     */
    public function toString()
    {

        // Lvd.
        $queries = [];
        $model   = $this->getModel();
        $fields  = $model->getFields();

        // Go with each records.
        foreach ($this->getInstances() as $instance) {

            // Short track.
            if ($instance->grabHaveAnythingChanged() === false) {
                continue;
            }

            // Lvd.
            $fieldsToAdd = [];

            // Work on each field.
            foreach ($fields as $field) {

                // Ignore PK fields - do not change them.
                if ($field->isPrimaryKey() === true && $instance->grabPkFieldHasChanged() === false) {
                    continue;
                }

                // Lvd.
                $getterName = $field->getGetterName();
                $value      = $this->valueify($instance->$getterName());

                // Add this field.
                $fieldsToAdd[] = '`' . $field->getName() . '`=' . $value;
            }

            // Get Primary Key.
            $pkField = $model->getPrimaryKeyField()->getName();

            // Get Primary Key value.
            if ($instance->grabPkFieldHasChanged() === false) {
                $pkValue = $instance->grabPkValue();
            } else {
                $pkValue = $instance->grabPkPreviousValue();
            }

            // Create query.
            $query  = 'UPDATE ';
            $query .= '`' . $model->getName() . '`';
            $query .= ' SET ';
            $query .= implode(', ', $fieldsToAdd);
            $query .= ' WHERE `' . $pkField . '`=' . $this->valueify($pkValue) . ';';

            // Add this query to set.
            $queries[] = $query;
        }//end foreach

        return trim(implode(PHP_EOL, $queries));
    }

    /**
     * Call query and wait for response.
     *
     * @return boolean|mysqli_result
     */
    public function call()
    {

        return $this->engineCallQuery();
    }

    /**
     * Call mulit query and wait for response.
     *
     * @return boolean True.
     */
    public function callMulti()
    {

        return $this->engineCallMultiQuery();
    }

    /**
     * Call query without waiting for any response.
     *
     * @return boolean True.
     */
    public function fire() : bool
    {

        return $this->engineFireQuery();
    }
}
