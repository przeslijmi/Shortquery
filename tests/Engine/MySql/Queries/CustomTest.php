<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Engine\MySql\Queries\CustomQuery;
use Przeslijmi\Shortquery\Exceptions\Engines\MySql\QueryFopConnectionDonoexException;
use Przeslijmi\Shortquery\Exceptions\Engines\MySql\QueryFopException;
use Przeslijmi\Shortquery\Exceptions\Engines\MySql\ValuifyFopException;
use Przeslijmi\Shortquery\ForTests\Models\Car;
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
        $this->expectException(ValuifyFopException::class);

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
        $this->expectException(QueryFopConnectionDonoexException::class);

        // Test.
        $query->call();
    }

    /**
     * Test if calling multi query on wrong database throw.
     *
     * @return void
     */
    public function testIfCallingMultiToWrongDatabaseThrows() : void
    {

        // Create.
        $query = new CustomQuery('testWrong');
        $query->set('SELECT COUNT(*) FROM `nonexisting_table`;');

        // Prepare.
        $this->expectException(QueryFopConnectionDonoexException::class);

        // Test.
        $query->callMulti();
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
        $this->expectException(QueryFopConnectionDonoexException::class);

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
        $this->expectException(QueryFopException::class);

        // Test.
        $this->assertTrue($query->fire());
    }

    /**
     * Test if calling empty query uses fast lane.
     *
     * @return void
     */
    public function testIfCallingEmptyQueryWorks() : void
    {

        // Preapre.
        $custom = new CustomQuery('test');
        $custom->set('');

        // Test.
        $this->assertTrue($custom->call());
        $this->assertEquals([], $custom->callMulti());
    }

    /**
     * Test if calling multi query works.
     *
     * @return void
     */
    public function testIfCallingMultiQueryWorks() : void
    {

        // Lvd.
        $query  = 'UPDATE `cars` SET `is_fast`=\'no\' WHERE `pk`=\'1\';';
        $query .= 'UPDATE `cars` SET `is_fast`=\'yes\' WHERE `pk`=\'1\';';

        // Prepare.
        $custom = new CustomQuery('test');
        $custom->set($query);
        $custom->callMulti();

        // Read.
        $car = new Car();
        $car->setPk(1);
        $car->read();

        // Test.
        $this->assertEquals('yes', $car->getIsFast());
    }

    /**
     * Test if calling multi query works.
     *
     * @return void
     */
    public function testIfCallingMultiQueryWorksTwo() : void
    {

        // Lvd.
        $query  = 'SELECT * FROM `cars` ORDER BY `pk` LIMIT 0,1;';
        $query .= 'SELECT * FROM `girls` ORDER BY `pk` LIMIT 0,1;';

        // Prepare.
        $custom = new CustomQuery('test');
        $custom->set($query);
        $result = $custom->callMulti();

        // Test.
        $this->assertIsArray($result);
        $this->assertEquals(2, count($result));
        $this->assertInstanceOf('mysqli_result', $result[0]);
        $this->assertInstanceOf('mysqli_result', $result[1]);
        $this->assertEquals('1', $result[0]->fetch_assoc()['pk']);
        $this->assertEquals('1', $result[1]->fetch_assoc()['pk']);
    }

    /**
     * Test if calling multi query that is wrong throws.
     *
     * @return void
     */
    public function testIfWrongCallingMultiQueryThrows() : void
    {

        // Lvd.
        $query = 'WRONG STATEMENT `cars`;';

        // Prepare.
        $custom = new CustomQuery('test');
        $custom->set($query);

        // Prepare.
        $this->expectException(QueryFopException::class);

        // Test.
        $this->assertTrue($custom->callMulti());
    }
}
