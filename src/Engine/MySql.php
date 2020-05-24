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
     * Last insert id object if needed.
     *
     * @var integer
     */
    protected $lastInsertId;

    /**
     * Method of calling query including waiting for response.
     *
     * @throws MethodFopException On mysqliQueryCantBeCalledWhileNoConnection.
     * @throws MethodFopException On mysqliQueryWrosyn.
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
        } catch (ClassFopException $e) {
            throw (new MethodFopException('mysqliQueryCantBeCalledWhileNoConnection', $e))->addInfo('query', $query);
        }

        // Log.
        if (substr(trim($query), 0, 6) !== 'SELECT') {
            Log::get()->notice($query);
        }

        // Call query.
        $result = $mysqli->query($query);

        // Save last insert_id.
        $this->lastInsertId = (int) $mysqli->insert_id;

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

        // Fast lane.
        if (empty($query) === true) {
            return true;
        }

        // Check connection.
        try {
            $mysqli = Connection::get($this->database);
        } catch (ClassFopException $e) {
            throw (new MethodFopException('mysqliQueryCantBeCalledWhileNoConnection', $e))->addInfo('query', $query);
        }

        // Log.
        Log::get()->notice($query);

        // Call query.
        $result = $mysqli->query($query);

        // Save last insert_id.
        $this->lastInsertId = (int) $mysqli->insert_id;

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

    /**
     * Method of calling multi query including waiting for the response.
     *
     * @throws MethodFopException On mysqliQueryCantBeCalledWhileNoConnection.
     * @throws MethodFopException On mysqliQueryWrosyn.
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

        // Log.
        Log::get()->notice($query);

        // Check connection.
        try {
            $mysqli = Connection::get($this->database);
        } catch (ClassFopException $e) {
            throw (new MethodFopException('mysqliQueryCantBeCalledWhileNoConnection', $e))->addInfo('query', $query);
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
            throw (new MethodFopException('mysqliQueryWrosyn'))
                ->addInfo('query', trim($query))
                ->addInfo('errorNo', (string) $mysqli->errno)
                ->addInfo('error', $mysqli->error);
        }
        $mysqli->close();

        return $result;
    }
}
