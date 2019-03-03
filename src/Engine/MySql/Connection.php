<?php

namespace Przeslijmi\Shortquery\Engine\Mysql;

use MySQLi;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;

/**
 * Connection to MySqli creator.
 */
class Connection
{

    /**
     * Instances of connections.
     *
     * @var   array
     * @since v1.0
     */
    private static $connections = [];

    /**
     * Gets (and creates if needed) connection.
     *
     * @param int $id (opt., 0) Id of instance of connection.
     *
     * @throws ClassFopException When connection is not established.
     * @return MySqli
     * @since  v1.0
     */
    public static function get(int $id=0) : MySQLi
    {

        // no instance with given id - create new one
        if (isset($connections[$id]) === false) {
            try {
                $connections[$id] = self::startConnection('localhost', 'user', 'user1234!', 'stolem', '3306');
            } catch (MethodFopException $e) {
                throw new ClassFopException('mysqliConnectionNotEstablished', $e);
            }
        }

        // return instance of given id
        return $connections[$id];
    }

    /**
     * Starts new connection if it is needed.
     *
     * @param string $host     Host of DB.
     * @param string $user     User name.
     * @param string $password Password (open text!).
     * @param string $database Name of database.
     * @param int    $port     (opt., 3306) Port for database.
     *
     * @throws MethodFopException When connection is not established.
     * @return MySqli
     * @since  v1.0
     */
    private static function startConnection(string $host, string $user, string $password, string $database, int $port=3306) : MySqli
    {

        // try to connect
        $connection = @new MySQLi($host, $user, $password, $database, $port);

        // if there was an error - throw exception
        if (empty($connection->connect_error) === false) {
            throw (new MethodFopException('mysqliConnectionError'))
                ->addInfo('errorNo', $connection->connect_errno)
                ->addInfo('error', trim($connection->connect_error))
                ->addInfo('host', $host)
                ->addInfo('user', $user)
                ->addInfo('usingPassword', [ 'NO', 'YES' ][(bool) $password])
                ->addInfo('database', $database)
                ->addInfo('port', $port);
        }

        return $connection;
    }
}
