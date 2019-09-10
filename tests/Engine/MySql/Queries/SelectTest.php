<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Engine\Mysql\Queries\SelectQuery;
use Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel;

/**
 * Methods for testing SelectQuery from MySql Engine.
 */
final class SelectTest extends TestCase
{

    public function testIfWorks()
    {

        // Create Query.
        $query = new SelectQuery(( new GirlModel() ));
        $query->setLimit(5, 5);
        $query->addFields('girls.name', 'girls.webs');
        $query->addVals([ 'wii', 'constant' ]);
        $query->addVals('nonAliasVal');
        $query->call();

        // Get records.
        $records = $query->read();

        // Test.
        $this->assertEquals('Krystal', $records[0]['name']);
        $this->assertEquals('wii', $records[0]['constant']);
    }

    public function testIfGroupAndOrderByWorks1()
    {

        // Create Query.
        $query = new SelectQuery(( new GirlModel() ));
        $query->setLimit(0, 5);
        $query->addField('girls.name', true, true, true);
        $query->addFunc('count', [ 'pk' ])->setAlias('how_many');
        $query->addVal('hello')->setAlias('greeting');
        $query->call();

        // Get records.
        $records = $query->read();

        // Test.
        $this->assertEquals('Adriana', $records[0]['name']);
        $this->assertEquals(1, $records[0]['how_many']);
        $this->assertEquals('hello', $records[0]['greeting']);
    }

    public function testIfGroupAndOrderByWorks2()
    {

        // Create Query.
        $query = new SelectQuery(( new GirlModel() ));
        $query->setLimit(0, 5);
        $query->addFunc('count', [])->setAlias('count');
        $query->addFunc('concat', [ '`webs`' ], true, true, true)->setAlias('websites');
        $query->call();

        // Get records.
        $records = $query->read();

        // Test.
        $this->assertEquals('2', $records[0]['count']);
        $this->assertEquals('fb', $records[0]['websites']);
    }
}
