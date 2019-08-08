<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\Queries;

use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Engine\MySql;
use Przeslijmi\Shortquery\Engine\MySql\Query;

/**
 * Tool for creating INSERT query (including its string representation).
 */
class InsertQuery extends Query
{

    private $addedPk;

    /**
     * Converts INSERT query into string.
     *
     * @since  v1.0
     * @return string
     */
    public function toString()
    {

        // Lvd.
        $recordsToAdd = [];
        $columnsToAdd = [];
        $fields       = $this->getModel()->getFields();

        // Go with each records.
        foreach ($this->getInstances() as $instance) {

            foreach ($fields as $field) {
                $columnsToAdd[] = $field->getName();
            }

            break;
        }

        // Go with each records.
        foreach ($this->getInstances() as $instance) {

            // Lvd.
            $fieldsToAdd = [];

            // Work on each field.
            foreach ($fields as $field) {

                // Lvd.
                $getterName = $field->getGetterName();

                if ($field->isPrimaryKey() === true && $instance->hasPrimaryKey() === false) {
                    $fieldsToAdd[] = $this->valueify(null);
                } else {
                    $fieldsToAdd[] = $this->valueify($instance->$getterName());
                }
            }

            // Save record.
            $recordsToAdd[] = '(' . implode(', ', $fieldsToAdd) . ')';
        }//end foreach

        // Create query.
        $query  = 'INSERT INTO ';
        $query .= '`' . $this->getModel()->getName() . '`';
        $query .= ' (`' . implode('`, `', $columnsToAdd) . '`)';
        $query .= ' VALUES ';
        $query .= implode(', ', $recordsToAdd) . ';';

        return $query;
    }

    public function call()
    {

        $this->engineCallQuery();

        foreach ($this->getInstances() as $instance) {
            $instance->defineIsAdded(true);
        }
    }

    public function fire()
    {

        $this->engineFireQuery();

        foreach ($this->getInstances() as $instance) {
            $instance->defineIsAdded(true);
        }
    }

    protected function setAddedPk(int $addedPk)
    {

        $this->addedPk = $addedPk;
    }

    public function getAddedPk(int $forRecord = 0) : int
    {

        return ( $this->addedPk - $forRecord );
    }
}
