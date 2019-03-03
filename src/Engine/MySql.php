<?php

namespace Przeslijmi\Shortquery\Engine;

use mysqli_result;
use Przeslijmi\Shortquery\Engine;
use Przeslijmi\Shortquery\Engine\EngineInterface;
use Przeslijmi\Shortquery\Engine\Mysql\Connection;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

use Przeslijmi\Shortquery\Engine\MySql\Queries\SelectQuery;
use Przeslijmi\Shortquery\Engine\MySql\Queries\InsertQuery;

class MySql extends Engine implements EngineInterface
{

    public function create() : void
    {

        $queryObject = new InsertQuery($this);
        $query = $queryObject->toString();

        $this->fireQuery($query);
    }

    public function read() : array
    {

        $queryObject = new SelectQuery($this);
        $query = $queryObject->toString();

        // get results
        $result = $this->callQuery($query);
        $array = $result->fetch_all(MYSQLI_ASSOC);

        return $array;
    }

    private function callQuery(string $query) : mysqli_result
    {

        try {
            $mysqli = Connection::get();
        } catch (ClassFopException $e) {
            throw (new MethodFopException('mysqliQueryCantBeCalledWhileNoConnection', $e))->addInfo('query', $query);
        }

        $result = $mysqli->query($query);

        if ($result === false) {
            throw (new MethodFopException('mysqliQueryWrosyn'))
                ->addInfo('query', trim($query))
                ->addInfo('errorNo', $mysqli->errno)
                ->addInfo('error', $mysqli->error);
        }

        return $result;
    }

    private function fireQuery(string $query) : bool
    {

        try {
            $mysqli = Connection::get();
        } catch (ClassFopException $e) {
            throw (new MethodFopException('mysqliQueryCantBeCalledWhileNoConnection', $e))->addInfo('query', $query);
        }

        $result = $mysqli->query($query);

        if ($result === false) {
            throw (new MethodFopException('mysqliQueryWrosyn'))
                ->addInfo('query', trim($query))
                ->addInfo('errorNo', $mysqli->errno)
                ->addInfo('error', $mysqli->error);
        }

        return true;
    }
}
