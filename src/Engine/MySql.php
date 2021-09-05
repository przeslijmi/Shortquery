<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine;

use mysqli_result;
use Przeslijmi\Shortquery\Engine;
use Przeslijmi\Shortquery\Engine\EngineInterface;
use Przeslijmi\Shortquery\Engine\MySql\Connection;
use Przeslijmi\Shortquery\Exceptions\Engines\MySql\ConnectionFopException;
use Przeslijmi\Shortquery\Exceptions\Engines\MySql\QueryFopException;
use Przeslijmi\Shortquery\Exceptions\Engines\MySql\QueryFopConnectionDonoexException;

/**
 * Engine creating queries for MySql language.
 */
abstract class MySql extends Engine implements EngineInterface
{

    /**
     * Last insert id object if needed.
     *
     * @var integer
     */
    protected $lastInsertId;

    /**
     * Method of calling query including waiting for response.
     *
     * @throws QueryFopConnectionDonoexException When query cant be sent because no connection is set.
     * @throws QueryFopException When query is not working.
     * @return boolean|mysqli_result
     *
     * @phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps
     * @phpcs:disable Zend.NamingConventions.ValidVariableName.NotCamelCaps
     */
    protected function engineCallQuery()
    {

        // Lvd.
        $query = $this->toString();

        // Fast lane.
        if (empty($query) === true) {
            return true;
        }

        // Check connection.
        try {
            $mysqli = Connection::get($this->database);
        } catch (ConnectionFopException $sexc) {
            throw new QueryFopConnectionDonoexException([ $query ], 0, $sexc);
        }

        // Call query.
        $result = $mysqli->query($query);

        // Save last insert_id.
        $this->lastInsertId = (int) $mysqli->insert_id;

        // Throw when result is false.
        if ($result === false) {
            throw new QueryFopException([
                trim($query),
                (string) $mysqli->errno,
                $mysqli->error,
            ]);
        }

        return $result;
    }

    /**
     * Method of calling query without waiting for any response.
     *
     * @throws QueryFopConnectionDonoexException When query cant be sent because no connection is set.
     * @throws QueryFopException When query is not working.
     * @return mysqli_result
     *
     * @phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps
     * @phpcs:disable Zend.NamingConventions.ValidVariableName.NotCamelCaps
     */
    protected function engineFireQuery() : bool
    {

        // Lvd.
        $query = $this->toString();

        // Fast lane.
        if (empty($query) === true) {
            return true;
        }

        // Check connection.
        try {
            $mysqli = Connection::get($this->database);
        } catch (ConnectionFopException $sexc) {
            throw new QueryFopConnectionDonoexException([ $query ], 0, $sexc);
        }

        // Call query.
        $result = $mysqli->query($query);

        // Save last insert_id.
        $this->lastInsertId = (int) $mysqli->insert_id;

        // Throw when result is false.
        if ($result === false) {
            throw new QueryFopException([
                trim($query),
                (string) $mysqli->errno,
                $mysqli->error,
            ]);
        }

        if (method_exists($this, 'setAddedPk') === true) {
            $this->setAddedPk($mysqli->insert_id);
        }

        return true;
    }

    /**
     * Method of calling multi query including waiting for the response.
     *
     * @throws QueryFopConnectionDonoexException When query cant be sent because no connection is set.
     * @throws QueryFopException When query is not working.
     * @return array
     *
     * @phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps
     * @phpcs:disable Zend.NamingConventions.ValidVariableName.NotCamelCaps
     */
    protected function engineCallMultiQuery() : array
    {

        // Lvd.
        $query  = $this->toString();
        $result = [];

        // Fast lane.
        if (empty($query) === true) {
            return [];
        }

        // Check connection.
        try {
            $mysqli = Connection::get($this->database);
        } catch (ConnectionFopException $sexc) {
            throw new QueryFopConnectionDonoexException([ $query ], 0, $sexc);
        }

        // Call query.
        if ($mysqli->multi_query($query) !== false) {
            do {

                // Get result.
                $resultOfThis = $mysqli->store_result();

                // Save result for return.
                $result[] = $resultOfThis;
            } while ($mysqli->more_results() === true && $mysqli->next_result() === true);
        } else {
            throw new QueryFopException([
                trim($query),
                (string) $mysqli->errno,
                $mysqli->error,
            ]);
        }
        $mysqli->close();

        return $result;
    }
}
