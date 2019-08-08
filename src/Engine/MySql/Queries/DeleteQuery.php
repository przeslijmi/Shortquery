<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\Queries;

use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Engine\MySql;
use Przeslijmi\Shortquery\Engine\MySql\Query;
use Przeslijmi\Shortquery\Engine\Mysql\ToString\LogicsToString;
use Przeslijmi\Shortquery\Items\Rule;

/**
 * Tool for creating DELETE query (including its string representation).
 */
class DeleteQuery extends Query
{

    private $addedPk;

    /**
     * Converts DELETE query into string.
     *
     * @since  v1.0
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

    public function call()
    {

        $this->engineCallQuery();
    }

    public function fire()
    {

        $this->engineFireQuery();
    }
}
