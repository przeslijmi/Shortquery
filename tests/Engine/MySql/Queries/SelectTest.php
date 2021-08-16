<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Engine\MySql\Queries\SelectQuery;
use Przeslijmi\Shortquery\ForTests\Models\Car;
use Przeslijmi\Shortquery\ForTests\Models\Cars;
use Przeslijmi\Shortquery\ForTests\Models\Core\CarModel;
use Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel;

/**
 * Methods for testing SelectQuery from MySql Engine.
 */
final class SelectTest extends TestCase
{

    /**
     * Test if simple select works.
     *
     * @return void
     */
    public function testIfWorks() : void
    {

        // Create Query.
        $query = new SelectQuery(new GirlModel());
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

    /**
     * Test if relation select works.
     *
     * @return void
     */
    public function testIfRelationWorksWithAllFields() : void
    {

        // Lvd.
        $keysExpected = [ 'pk', 'name', 'webs', 'cars.pk', 'cars.owner_girl', 'cars.is_fast', 'cars.name' ];

        // Create Query.
        $query = new SelectQuery(new GirlModel());
        $query->setLimit(0, 1);
        $query->addRelation('cars');
        $query->call();

        // Get records.
        $records = $query->read();

        // Test.
        $this->assertEquals($keysExpected, array_keys($records[0]));
        $this->assertEquals('Adriana', $records[0]['name']);
    }

    /**
     * Test if using `group` and `order by` works.
     *
     * @return void
     */
    public function testIfGroupAndOrderByWorks1() : void
    {

        // Create Query.
        $query = new SelectQuery(new GirlModel());
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

    /**
     * Test if using `group` and `order by` works.
     *
     * @return void
     */
    public function testIfGroupAndOrderByWorks2() : void
    {

        // Create Query.
        $query = new SelectQuery(new GirlModel());
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

    /**
     * Test if relation works.
     *
     * @return void
     */
    public function testIfRelationWorks() : void
    {

        // Create Query.
        $query = new SelectQuery(( new CarModel() ));
        $query->addRelation('oneOwnerGirl');
        $query->addField('girls.name')->setAlias('girls_name');
        $query->addField('cars.is_fast');
        $query->addField('cars.name')->setAlias('cars_name');
        $query->addRule('girls.name', 'Adriana');
        $query->call();

        // Get records.
        $records = $query->read();

        // Expect query contents.
        $sqlQuery  = 'SELECT `girls`.`name` AS `girls_name`, `cars`.`is_fast`, `cars`.`name` AS `cars_name` ';
        $sqlQuery .= 'FROM `cars` LEFT JOIN `girls` ON `cars`.`owner_girl`=`girls`.`pk` WHERE (`girls`.`name`';
        $sqlQuery .= '=\'Adriana\');';

        // Test.
        $this->assertEquals('2', count($records));
        $this->assertEquals('Adriana', $records[0]['girls_name']);
        $this->assertEquals('Adriana', $records[1]['girls_name']);
        $this->assertEquals($sqlQuery, $query->toString());
    }

    /**
     * Test if creating collection from precreated instances will work.
     *
     * @return void
     */
    public function testIfReadingIntoCollectionWithPrecreatedInstanceWorks() : void
    {

        // Precreate instance.
        $car = new Car();
        $car->setPk(3);

        // Get id of this instance.
        $idOfPrecreated = spl_object_id($car);

        // Create collection with above instance put.
        $cars = new Cars();
        $cars->getLogics()->addRule('pk', 3);
        $cars->put($car);
        $cars->read();

        // Get instance from collection - it should be filled with data.
        $car = $cars->getOne();

        // Get id of this instance - it should be identical.
        $idOfPostcreated = spl_object_id($car);

        // Test.
        $this->assertEquals(3, $car->getPk());
        $this->assertEquals(2, $car->getOwnerGirl());
        $this->assertEquals('yes', $car->getIsFast());
        $this->assertEquals('Opel', $car->getName());
        $this->assertEquals($idOfPrecreated, $idOfPostcreated);
    }

    /**
     * Test if firing query works - it has no sense on SELECT query - but ... ok?
     *
     * @return void
     */
    public function testIfFiringQueryWorks() : void
    {

        // Fire query.
        $query = new SelectQuery(( new CarModel() ));
        $query->fire();

        // Test.
        $this->assertTrue(true);
    }

    /**
     * Test if read by (grouping single on key) works.
     *
     * @return void
     */
    public function testIfReadByWorks() : void
    {

        // Create Query.
        $query = new SelectQuery(new GirlModel());
        $query->call();

        // Get records.
        $pks = $query->readBy('pk');

        foreach ($pks as $pk => $girl) {
            $this->assertEquals($pk, $girl['pk']);
        }
    }

    /**
     * Test if read by (grouping single on key) fire warning when key has more than one record.
     *
     * @return void
     */
    public function testIfReadByFireWarning() : void
    {

        // Create Query.
        $query = new SelectQuery(new GirlModel());
        $query->call();

        // Get records.
        $webs = $query->readBy('webs');

        foreach ($webs as $web => $girl) {
            $this->assertEquals($web, $girl['webs']);
        }
    }

    /**
     * Test if read multiple by (grouping multiple on key) works.
     *
     * @return void
     */
    public function testIfReadMulByWorks() : void
    {

        // Create Query.
        $query = new SelectQuery(new CarModel());
        $query->call();

        // Get records.
        $isFasts = $query->readMultipleBy('is_fast');

        // Test.
        foreach ($isFasts as $isFast => $cars) {
            foreach ($cars as $car) {
                $this->assertEquals($isFast, $car['is_fast']);
            }
        }
    }
}
