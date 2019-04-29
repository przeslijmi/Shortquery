<?php

namespace Przeslijmi\Shortquery\Engine\MySql\Queries;

use Przeslijmi\Shortquery\Engine\MySql;
use Przeslijmi\Shortquery\Engine\Mysql\ToString\LogicsToString;

/**
 * Tool for creating SELECT query (including its string representation).
 */
class SelectQuery
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
     * Converts SELECT query into string.
     *
     * @since  v1.0
     * @return string
     */
    public function toString()
    {

        // Prepare query.
        $where  = ( new LogicsToString($this->collection->getLogics()) )->toWhereString();
        $string = 'SELECT * FROM ' . $this->collection->getModel()->getName() . $where . ';';

        return $string;
    }
}
