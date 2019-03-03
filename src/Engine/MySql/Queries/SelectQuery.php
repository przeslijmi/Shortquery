<?php

namespace Przeslijmi\Shortquery\Engine\MySql\Queries;

use Przeslijmi\Shortquery\Engine\Mysql\ToString\LogicsToString;
use Przeslijmi\Shortquery\Engine\MySql;

class SelectQuery
{

    private $engine;
    private $collection;

    public function __construct(MySql $engine)
    {

        $this->engine = $engine;
        $this->collection = $engine->getCollection();

    }

    public function toString()
    {

        // prepare query
        $where = (new LogicsToString($this->collection->getLogics()))->toWhereString();
        $string = 'SELECT * FROM ' . $this->collection->getModel()->getName() . $where . ';';

        return $string;
    }
}
