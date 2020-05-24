<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\Queries;

use Przeslijmi\Shortquery\Engine\MySql\Query;
use Przeslijmi\Shortquery\Data\Model;

/**
 * Tool for creating UPDATE query (including its string representation).
 */
class CustomQuery extends Query
{

    /**
     * Contents of the query.
     *
     * @var string
     */
    private $query;

    /**
     * Constructor.
     *
     * @param string $database Name of database (see: PRZESLIJMI_SHORTQUERY_DATABASES).
     */
    public function __construct(string $database)
    {

        parent::__construct(new Model('nonExisting'), $database);
    }

    /**
     * Setter for query contents.
     *
     * @param string $query Contents of the query.
     *
     * @return self
     */
    public function set(string $query) : self
    {

        // Save.
        $this->query = $query;

        return $this;
    }

    /**
     * Converts UPDATE query into string.
     *
     * @return string
     */
    public function toString()
    {

        return $this->query;
    }

    /**
     * Return records in a simple array.
     *
     * @return array
     */
    public function read()
    {

        // Lvd.
        $array  = [];
        $result = $this->call();

        // Go through every record and put it into final array.
        while (( $record = $result->fetch_assoc() ) !== null) {
            $array[] = $record;
        }

        return $array;
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
