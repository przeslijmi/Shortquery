<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use Exception;
use PHPUnit\Framework\TestCase;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Shortquery\Engine\Mysql\Queries\CustomQuery;
use stdClass;

/**
 * Methods for testing CustomQuery from MySql Engine.
 */
final class CustomTest extends TestCase
{

    /**
     * Test if creating custom query works.
     *
     * @return void
     */
    public function testIfCreationWorks() : void
    {

        // Lvd.
        $query = 'SELECT MIN(`pk`) AS `minimum` FROM `cars`;';

        // Prepare query.
        $custom = new CustomQuery('test');
        $custom->set($query);

        // Check integrity.
        $this->assertEquals($query, $custom->toString());

        // Fire query.
        $this->assertTrue($custom->fire());

        // Call query and get results.
        $custom->call();
        $results = $custom->read();

        // Test results.
        $this->assertEquals('1', $results[0]['minimum']);
    }

    /**
     * Tests valueify method works.
     *
     * @return void
     */
    public function testIfValuifyMethodWorks() : void
    {

        // Lvd.
        $stdClass       = new stdClass();
        $stdClass->valA = 'string';
        $stdClass->valB = 5.00;

        // Create.
        $query = new CustomQuery('test');

        // Test.
        $this->assertEquals('NULL', $query->valueify(null));
        $this->assertEquals('\'string\'', $query->valueify('string'));
        $this->assertEquals('1', $query->valueify(true));
        $this->assertEquals('0', $query->valueify(false));
        $this->assertEquals('5', $query->valueify(5));
        $this->assertEquals('\'{"valA":"string","valB":5}\'', $query->valueify($stdClass));
    }

    /**
     * Tests id valueify method throws.
     *
     * @return void
     */
    public function testIfValuifyMethodThrows() : void
    {

        // Lvd.
        $anonymousClass = new class
        {

            /**
             * Only for testing purposes.
             *
             * @return boolean
             */
            public function test() : bool
            {
                return true;
            }
        };

        // Create.
        $query = new CustomQuery('test');

        // Prepare.
        $this->expectException(Exception::class);

        // Test.
        $query->valueify($anonymousClass);
    }

    /**
     * Test if calling query on wrong database throw.
     *
     * @return void
     */
    public function testIfCallingToWrongDatabaseThrows() : void
    {

        // Create.
        $query = new CustomQuery('testWrong');
        $query->set('SELECT COUNT(*) FROM `nonexisting_table`;');

        // Prepare.
        $this->expectException(MethodFopException::class);

        // Test.
        $query->call();
    }

    /**
     * Test if firing query on wrong database throw.
     *
     * @return void
     */
    public function testIfFiringToWrongDatabaseThrows() : void
    {

        // Create.
        $query = new CustomQuery('testWrong');
        $query->set('SELECT COUNT(*) FROM `nonexisting_table`;');

        // Prepare.
        $this->expectException(MethodFopException::class);

        // Test.
        $query->fire();
    }

    /**
     * Test if firing empty query returns true.
     *
     * @return void
     */
    public function testIfFiringEmptyQueryWorks() : void
    {

        // Create.
        $query = new CustomQuery('test');

        // Test.
        $this->assertTrue($query->fire());
    }

    /**
     * Test if firing wrong query returns true.
     *
     * @return void
     */
    public function testIfFiringWrongQueryWorks() : void
    {

        // Create.
        $query = new CustomQuery('test');
        $query->set('WRONG QUERY;');

        // Prepare.
        $this->expectException(MethodFopException::class);

        // Test.
        $this->assertTrue($query->fire());
    }
}
