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
     *
     * @since v1.0
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
     * @since  v1.0
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
     * @since  v1.0
     * @return string
     */
    public function toString()
    {

        return $this->query;
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
