<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine;

use mysqli_result;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Shortquery\Engine;
use Przeslijmi\Shortquery\Engine\EngineInterface;
use Przeslijmi\Shortquery\Engine\Mysql\Connection;
use Przeslijmi\Silogger\Log;

/**
 * Engine creating queries for MySql language.
 */
abstract class MySql extends Engine implements EngineInterface
{

    /**
     * Method for creating new records in database.
     *
     * @since  v1.0
     * @return void
     */
    /*public function create() : void
    {

        // Call to create query final syntax..
        $queryObject = new InsertQuery($this);

        $this->fire($queryObject->toString());
    }*/

    /**
     * Method for reading new records in database.
     *
     * @since  v1.0
     * @return array
     */
    /*public function read() : array
    {

        // Call to create query final syntax.
        $queryObject = new SelectQuery($this);

        // Get results.
        $result = $this->call($queryObject->toString());
        $array  = $result->fetch_all(MYSQLI_ASSOC);

        return $array;
    }*/

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
    protected function engineCallQuery() : mysqli_result
    {

        // Lvd.
        $query = $this->toString();

        // Check connection.
        try {
            $mysqli = Connection::get();
        } catch (ClassFopException $e) {
            throw (new MethodFopException('mysqliQueryCantBeCalledWhileNoConnection', $e))->addInfo('query', $query);
        }

        // Log.
        if (substr(trim($query), 0, 7) !== 'SELECT ') {
            Log::notice($query);
        }

        // Call query.
        $result = $mysqli->query($query);

        // Throw when result is false.
        if ($result === false) {
            throw (new MethodFopException('mysqliQueryWrosyn'))
                ->addInfo('query', trim($query))
                ->addInfo('errorNo', (string) $mysqli->errno)
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
     *
     * @phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps
     * @phpcs:disable Zend.NamingConventions.ValidVariableName.NotCamelCaps
     */
    protected function engineFireQuery() : bool
    {

        // Lvd.
        $query = $this->toString();

        if (empty($query) === true) {
            return true;
        }

        // Check connection.
        try {
            $mysqli = Connection::get();
        } catch (ClassFopException $e) {
            throw (new MethodFopException('mysqliQueryCantBeCalledWhileNoConnection', $e))->addInfo('query', $query);
        }

        // Log.
        Log::notice($query);

        // Call query.
        $result = $mysqli->multi_query($query);

        // Throw when result is false.
        if ($result === false) {
            throw (new MethodFopException('mysqliQueryWrosyn'))
                ->addInfo('query', trim($query))
                ->addInfo('errorNo', (string) $mysqli->errno)
                ->addInfo('error', $mysqli->error);
        }

        if (method_exists($this, 'setAddedPk') === true) {
            $this->setAddedPk($mysqli->insert_id);
        }

        return true;
    }
}
