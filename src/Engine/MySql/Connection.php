<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql;

use MySQLi;
use Przeslijmi\Shortquery\Exceptions\Engines\MySql\ConnectionFopException;
use Przeslijmi\Shortquery\Exceptions\Engines\MySql\MySqlNotAvailableException;
use Throwable;

/**
 * Connection to MySqli creator.
 */
class Connection
{

    /**
     * Instances of connections.
     *
     * @var array
     */
    private static $connections = [];

    /**
     * Gets (and creates if needed) connection.
     *
     * @param string  $database Name of database to get configs from (PRZESLIJMI_SHORTQUERY_DATABASES).
     * @param integer $id       Opt., 0. Id of instance of connection.
     *
     * @return MySqli
     */
    public static function get(string $database, int $id = 0, ?array $auth = null) : MySQLi
    {

        // Lvd.
        if ($auth === null) {
            $auth = PRZESLIJMI_SHORTQUERY_DATABASES[$database]['auth'];
        }
        $def = ( PRZESLIJMI_SHORTQUERY_DATABASES[$database]['def'] ?? [] );

        // No instance with given id - create new one.
        if (isset($connections[$database][$id]) === false) {

            // Connect.
            $connections[$database][$id] = self::startConnection(
                $auth['url'],
                $auth['user'],
                $auth['pass'],
                $auth['db'],
                ( $auth['port'] ?? null )
            );

            // Define charset.
            if (isset($def['charSet']) && empty($def['charSet']) === false) {
                $connections[$database][$id]->set_charset($def['charSet']);
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
     * @throws ConnectionFopException When connection is not established.
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
        ?int $port = 330
    ) : MySqli {

        // Try to connect.
        try {
            $connection = @new MySQLi($host, $user, $password, $database, $port);
        } catch (Throwable $thr) {
            throw new MySqlNotAvailableException(
                [ $host, $port, $user, [ 'NO', 'YES' ][ (bool) $password], $database ]
            );
        }

        // If there was an error - throw exception.
        if (empty($connection->connect_error) === false) {
            throw new ConnectionFopException([
                (string) $connection->connect_errno,
                trim($connection->connect_error),
                $host,
                $user,
                [ 'NO', 'YES' ][ (bool) $password],
                $database,
                (string) $port,
            ]);
        }

        return $connection;
    }
}
