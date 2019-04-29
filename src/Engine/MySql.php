<?php

namespace Przeslijmi\Shortquery\Engine;

use mysqli_result;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Shortquery\Engine;
use Przeslijmi\Shortquery\Engine\EngineInterface;
use Przeslijmi\Shortquery\Engine\Mysql\Connection;
use Przeslijmi\Shortquery\Engine\MySql\Queries\InsertQuery;
use Przeslijmi\Shortquery\Engine\MySql\Queries\SelectQuery;

/**
 * Engine creating queries for MySql language.
 */
class MySql extends Engine implements EngineInterface
{

    /**
     * Method for creating new records in database.
     *
     * @since  v1.0
     * @return void
     */
    public function create() : void
    {

        // Call to create query final syntax..
        $queryObject = new InsertQuery($this);

        $this->fireQuery($queryObject->toString());
    }

    /**
     * Method for reading new records in database.
     *
     * @since  v1.0
     * @return array
     */
    public function read() : array
    {

        // Call to create query final syntax..
        $queryObject = new SelectQuery($this);

        // Get results.
        $result = $this->callQuery($queryObject->toString());
        $array  = $result->fetch_all(MYSQLI_ASSOC);

        return $array;
    }

    /**
     * Method of calling query incl. waiting for response.
     *
     * @param string $query Query created by one of *Query objects.
     *
     * @since  v1.0
     * @throws MethodFopException On mysqliQueryCantBeCalledWhileNoConnection.
     * @throws MethodFopException On mysqliQueryWrosyn.
     * @return mysqli_result
     */
    private function callQuery(string $query) : mysqli_result
    {

        // Check connection.
        try {
            $mysqli = Connection::get();
        } catch (ClassFopException $e) {
            throw (new MethodFopException('mysqliQueryCantBeCalledWhileNoConnection', $e))->addInfo('query', $query);
        }

        // Call query.
        $result = $mysqli->query($query);

        // Throw when result is false.
        if ($result === false) {
            throw (new MethodFopException('mysqliQueryWrosyn'))
                ->addInfo('query', trim($query))
                ->addInfo('errorNo', $mysqli->errno)
                ->addInfo('error', $mysqli->error);
        }

        return $result;
    }

    /**
     * Method of calling query without waiting for any response.
     *
     * @param string $query Query created by one of *Query objects.
     *
     * @since  v1.0
     * @throws MethodFopException On mysqliQueryCantBeCalledWhileNoConnection.
     * @throws MethodFopException On mysqliQueryWrosyn.
     * @return mysqli_result
     */
    private function fireQuery(string $query) : bool
    {

        // Check connection.
        try {
            $mysqli = Connection::get();
        } catch (ClassFopException $e) {
            throw (new MethodFopException('mysqliQueryCantBeCalledWhileNoConnection', $e))->addInfo('query', $query);
        }

        // Call query.
        $result = $mysqli->query($query);

        // Throw when result is false.
        if ($result === false) {
            throw (new MethodFopException('mysqliQueryWrosyn'))
                ->addInfo('query', trim($query))
                ->addInfo('errorNo', $mysqli->errno)
                ->addInfo('error', $mysqli->error);
        }

        return true;
    }
}
