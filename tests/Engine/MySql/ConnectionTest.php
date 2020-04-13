<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\Mysql;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Engine\Mysql\Connection;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

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
        $this->expectException(ClassFopException::class);

        // Test.
        Connection::get('testWrong');
    }
}
