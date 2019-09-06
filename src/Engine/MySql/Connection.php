<?php declare(strict_types=1);

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
     * @param string  $database Name of database to get configs from (PRZESLIJMI_SHORTQUERY_DATABASES).
     * @param integer $id       Opt., 0. Id of instance of connection.
     *
     * @since  v1.0
     * @throws ClassFopException When connection is not established.
     * @return MySqli
     */
    public static function get(string $database, int $id = 0) : MySQLi
    {

        // Lvd.
        $auth = PRZESLIJMI_SHORTQUERY_DATABASES[$database]['auth'];

        // No instance with given id - create new one.
        if (isset($connections[$database][$id]) === false) {
            try {
                $connections[$database][$id] = self::startConnection(
                    $auth['url'],
                    $auth['user'],
                    $auth['pass'],
                    $auth['db'],
                    $auth['port']
                );
            } catch (MethodFopException $e) {
                throw new ClassFopException('mysqliConnectionNotEstablished', $e);
            }
        }

        // Return instance of given id.
        return $connections[$database][$id];
    }

    /**
     * Starts new connection if it is needed.
     *
     * @param string  $host     Host of DB.
     * @param string  $user     User name.
     * @param string  $password Password (open text!).
     * @param string  $database Name of database.
     * @param integer $port     Opt., 3306. Port for database.
     *
     * @since  v1.0
     * @throws MethodFopException When connection is not established.
     * @return MySqli
     *
     * @phpcs:disable Generic.PHP.NoSilencedErrors
     * @phpcs:disable Squiz.NamingConventions.ValidVariableName
     * @phpcs:disable PEAR.NamingConventions.ValidVariableName
     * @phpcs:disable Zend.NamingConventions.ValidVariableName
     */
    private static function startConnection(
        string $host,
        string $user,
        string $password,
        string $database,
        int $port = 330
    ) : MySqli {

        // Try to connect.
        $connection = @new MySQLi($host, $user, $password, $database, $port);

        // If there was an error - throw exception.
        if (empty($connection->connect_error) === false) {
            throw (new MethodFopException('mysqliConnectionError'))
                ->addInfo('errorNo', (string) $connection->connect_errno)
                ->addInfo('error', trim($connection->connect_error))
                ->addInfo('host', $host)
                ->addInfo('user', $user)
                ->addInfo('usingPassword', [ 'NO', 'YES' ][ (bool) $password])
                ->addInfo('database', $database)
                ->addInfo('port', (string) $port);
        }

        return $connection;
    }
}
