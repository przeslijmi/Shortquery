<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Engine\MySql\Connection;
use Przeslijmi\Shortquery\Exceptions\Engines\MySql\ConnectionFopException;

/**
 * Methods for testing Connection class.
 */
final class ConnectionTest extends TestCase
{

    /**
     * Test if giving proper credentials works.
     *
     * @return void
     */
    public function testIfProperCredentialWorks() : void
    {

        // Prepare.
        $connection = Connection::get('test');

        // Test.
        $this->assertTrue(is_a($connection, 'MySqli'));
    }

    /**
     * Test if giving wrong credentials throws.
     *
     * @return void
     */
    public function testIfWrongCredentialThrows() : void
    {

        // Prepare.
        $this->expectException(ConnectionFopException::class);

        // Test.
        Connection::get('testWrong');
    }
}
