<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\Queries;

use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Engine\MySql;
use Przeslijmi\Shortquery\Engine\MySql\Query;
use Przeslijmi\Shortquery\Engine\MySql\ToString\LogicsToString;
use Przeslijmi\Shortquery\Items\Rule;

/**
 * Tool for creating DELETE query (including its string representation).
 */
class DeleteQuery extends Query
{

    /**
     * Converts DELETE query into string.
     *
     * @return string
     */
    public function toString()
    {

        // Lvd.
        $pks = [];

        // Go with each records.
        foreach ($this->getInstances() as $instance) {
            $pks[] = $instance->grabPkValue();
        }

        // Add instances PK's to query.
        if (count($pks) > 0) {
            $this->addLogics(
                Rule::factoryWrapped(
                    $this->getModel()->getPrimaryKeyField()->getName(),
                    $pks
                )
            );
        }

        // Prepare where.
        $where = ( new LogicsToString($this->getLogicsSet()) )->toWhereString();

        // Create query.
        $query  = 'DELETE FROM ';
        $query .= '`' . $this->getModel()->getName() . '`';
        $query .= $where;
        $query .= ';';

        return $query;
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
     * Call query without waiting for any response.
     *
     * @return boolean True.
     */
    public function fire() : bool
    {

        return $this->engineFireQuery();
    }
}
