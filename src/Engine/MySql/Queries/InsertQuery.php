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

    /**
     * Primary key value for added record.
     *
     * @var integer
     */
    private $addedPk;

    /**
     * Converts INSERT query into string.
     *
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
        $query .= implode(',' . PHP_EOL, $recordsToAdd) . ';';

        return $query;
    }

    /**
     * Call query and wait for response.
     *
     * @return boolean|mysqli_result
     */
    public function call()
    {

        $response = $this->engineCallQuery();

        // Mark instances added and put primary keys (if possible).
        foreach ($this->getInstances() as $no => $instance) {

            // Set added.
            $instance->defineIsAdded(true);

            // Check if lastInsertId is present.
            if (empty($this->lastInsertId) === false) {
                $instance->definePkValue(( $this->lastInsertId + $no ));
            }
        }

        return $response;
    }

    /**
     * Call query without waiting for any response.
     *
     * @return boolean True.
     */
    public function fire()
    {

        $this->engineFireQuery();

        // Mark instances added.
        foreach ($this->getInstances() as $instance) {
            $instance->defineIsAdded(true);
        }

        return true;
    }

    /**
     * Setter for primary key value for added record.
     *
     * @param integer $addedPk Primary key value for added record.
     *
     * @return self
     */
    protected function setAddedPk(int $addedPk) : self
    {

        // Save.
        $this->addedPk = $addedPk;

        return $this;
    }

    /**
     * Getter for primary key value for added record.
     *
     * @return null|integer
     */
    public function getAddedPk() : ?int
    {

        return $this->addedPk;
    }
}
