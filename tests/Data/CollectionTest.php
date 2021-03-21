<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Exceptions\Data\CollectionCantBeCreatedException;
use Przeslijmi\Shortquery\Exceptions\Data\CollectionSliceNotPossibleException;
use Przeslijmi\Shortquery\Exceptions\Data\CollectionCantBeReadException;
use Przeslijmi\Shortquery\Exceptions\Items\LogicCreationFopException;
use Przeslijmi\Shortquery\Exceptions\Items\RuleCreationFopException;
use Przeslijmi\Shortquery\ForTests\Models\Cars;
use Przeslijmi\Shortquery\ForTests\Models\Girls;
use Przeslijmi\Shortquery\ForTests\Models\Girl;
use Przeslijmi\Shortquery\ForTests\Models\Things;

/**
 * Methods for testing Collections of Shortquery class.
 */
final class CollectionTest extends TestCase
{

    /**
     * Test if Girls Collection can be properly read.
     *
     * @return void
     */
    public function testIfReadingGirlsTableWorks() : void
    {

        // Create new Collection and call it to be read.
        $girls = new Girls();
        $girls->read();

        // Tests.
        $this->assertEquals(12, count($girls->get()));
        $this->assertEquals(12, $girls->length());
        $this->assertEquals(13, count($girls->count([ 'name' ])));
        $this->assertEquals(1, count($girls->get(0, 1)));
        $this->assertInstanceOf('Przeslijmi\Shortquery\ForTests\Models\Girl', $girls->getOne());
        $this->assertInstanceOf('Przeslijmi\Shortquery\ForTests\Models\Girl', $girls->getByPk(1));
        $this->assertEquals('Adriana', $girls->getValuesByField('name')[0]);
        $this->assertEquals('Adriana', $girls->getValuesByField('getName', true)[0]);

        // Tests for getGroupedByField - first two are for multiple results, last one is not.
        // But because names are unique - result is identical.
        $this->assertEquals(12, count($girls->getGroupedByField('name')));
        $this->assertEquals(12, count($girls->getGroupedByField('getName', true)));
        $this->assertEquals(12, count($girls->getGroupedByField('name', false, false)));

        // Test if first records are identcal - both ways.
        $multipleMethod    = $girls->getGroupedByField('name')['Adriana'][0];
        $nonMultipleMethod = $girls->getGroupedByField('name', false, false)['Adriana'];
        $this->assertEquals($multipleMethod, $nonMultipleMethod);
    }

    /**
     * Test if counting (without reading records) Collection works.
     *
     * @return void
     */
    public function testIfCountingWithoutReadingWorks() : void
    {

        // Create new Collection and call it to be read.
        $girls = new Girls();

        // Tests.
        $this->assertEquals(12, $girls->count()['@@total']);
        $this->assertEquals(12, $girls->count([ 'name' ])['@@total']);
    }

    /**
     * Test if ordering before reading Collection works.
     *
     * @return void
     */
    public function testIfOrderingBeforeReadingWorks() : void
    {

        // Create new Collection and call it to be read.
        $girls = new Girls();
        $girls->readOrderedBy('name', 0, 3);

        // Tests.
        $this->assertEquals('Adriana', $girls->getOne(0)->getName());
        $this->assertEquals('Annabella', $girls->getOne(1)->getName());
        $this->assertEquals('Avah', $girls->getOne(2)->getName());
    }

    /**
     * Test if Girls Collection can handle Logics.
     *
     * @return void
     */
    public function testIfReadingGirlsWithLogicsWorks() : void
    {

        // Create new Collection and call it to be read.
        $girls = new Girls([ 'pk', 1 ]);
        $girls->getLogics()->addRule('pk', 'eq', 1);
        $girls->getLogics()->addRuleEq('name', 'Adriana');
        $girls->getLogics()->addRuleNeq('name', 'AdrianaNooo');
        $girls->getLogics()->addLogicOr([ 'name', 'neq', 'test' ], [ 'name', 'neq', 'testEither' ]);
        $girls->read();

        // Tests.
        $this->assertEquals(1, count($girls->get()));
        $this->assertEquals(5, $girls->getLogics()->length());
        $this->assertEquals('Adriana', $girls->getOne()->getName());
    }

    /**
     * Test if reading Girls Collection with slice and order works.
     *
     * @return void
     */
    public function testIfReadingWithSliceAndOrderWork() : void
    {

        // Create new Collection and call it to be read.
        $girls = new Girls();
        $girls->readOrderedBy('name', 0, 3);

        // Test.
        $this->assertEquals('Adriana', $girls->getOne(0)->getName());
        $this->assertEquals('Annabella', $girls->getOne(1)->getName());
        $this->assertEquals('Avah', $girls->getOne(2)->getName());
    }

    /**
     * Test if reading Girls Collection with relation to Cars works.
     *
     * @return void
     */
    public function testIfReadingGirlsWithRelationToCarsWorks() : void
    {

        // Create new Collection and call it to be read.
        $girls = new Girls([ 'pk', 1 ]);
        $girls->read();
        $girls->expandCars();

        // Take girl.
        $girl = $girls->getOne(0);

        // Test.
        $this->assertEquals(2, $girl->getCars()->length());
    }

    /**
     * Test if reading Cars Collection with relation to GirlOwners works.
     *
     * @return void
     */
    public function testIfReadingCarsWithRelationToGirlOwnersWorks() : void
    {

        // Create new Collection and call it to be read.
        $cars = new Cars([ 'pk', 1 ]);
        $cars->read();
        $cars->expandOneOwnerGirl();

        // Take car.
        $car = $cars->getOne(0);

        // Test.
        $this->assertEquals('Adriana', $car->getOneOwnerGirl()->getName());
    }

    /**
     * Test if counting works.
     *
     * @return void
     */
    public function testIfCountingCarsWorks() : void
    {

        // Create new Collection and call it to be read.
        $cars = new Cars();

        // Test.
        $this->assertEquals(5, $cars->count()['@@total']);
        $this->assertEquals(5, $cars->count([ 'is_fast' ])['@@total']);
        $this->assertEquals(3, $cars->count([ 'is_fast' ])['yes']);
        $this->assertEquals(2, $cars->count([ 'is_fast' ])['no']);
    }

    /**
     * Test if trying to create wrong Rule Throws.
     *
     * @return void
     */
    public function testIfCreatingWrongRuleThrows1() : void
    {

        $this->expectException(RuleCreationFopException::class);

        // Create new Collection and call it to be read.
        $girls = new Girls();
        $girls->getLogics()->addRule();
    }

    /**
     * Test if trying to create wrong Rule Throws.
     *
     * @return void
     */
    public function testIfCreatingWrongRuleThrows2() : void
    {

        $this->expectException(LogicCreationFopException::class);

        // Create new Collection and call it to be read.
        $girls = new Girls();
        $girls->getLogics()->addLogicOr([]);
    }

    /**
     * Test if trying to create wrong Rule Throws.
     *
     * @return void
     */
    public function testIfCreatingWrongRuleThrows3() : void
    {

        $this->expectException(CollectionCantBeCreatedException::class);

        // Create new Collection and call it to be read.
        $girls = new Girls([]);
    }

    /**
     * Test if trying to create wrong Logic Throws.
     *
     * @return void
     */
    public function testIfCreatingWrongLogicThrows() : void
    {

        $this->expectException(LogicCreationFopException::class);

        // Create new Collection and call it to be read.
        $girls = new Girls();
        $girls->getLogics()->addLogicOr();
    }

    /**
     * Test if putting not Instances but simple arrays to Collection works.
     *
     * @return void
     */
    public function testIfPuttingArrayToCollectionWorks() : void
    {

        $girls = new Girls();

        $girls->putRecord([
            'name' => 'testName1',
            'webs' => 'fb',
        ]);
        $girls->putRecords([
            [
                'name' => 'testName2',
                'webs' => 'fb',
            ],
            [
                'name' => 'testName3',
                'webs' => 'fb',
            ],
        ]);

        $this->assertEquals(3, $girls->length());
        $this->assertEquals('testName1', $girls->getOne(0)->getName());
    }

    /**
     * Test if putting with automatic primary key creation works.
     *
     * @return void
     */
    public function testIfPuttingToCollectionWithAutopkWorks() : void
    {

        $girls = new Girls();

        $girl1 = new Girl();
        $girl1->setName('testName1');
        $girl1->setWebs('fb');

        $girl2 = new Girl();
        $girl2->setName('testName2');
        $girl2->setWebs('fb');

        $this->assertEquals(0, $girls->length());

        $girls->put($girl1, true);
        $girls->put($girl2, true);

        $this->assertEquals(2, $girls->length());
        $this->assertEquals(1, $girls->getOne(0)->grabPkValue());
        $this->assertEquals(2, $girls->getOne(1)->grabPkValue());
    }

    /**
     * Test if saving Collection works.
     *
     * @return void
     */
    public function testIfSavingCollectionWorks() : void
    {

        // New things.
        $things = new Things();
        $things->putRecord([
            'name' => 'something1'
        ]);
        $things->putRecord([
            'name' => 'something2'
        ]);

        // Save Collection.
        $things->save();

        // Test.
        $this->assertEquals('integer', gettype($things->getOne(0)->getPk()));
        $this->assertEquals('integer', gettype($things->getOne(1)->getPk()));

        // Change.
        $things->getOne(0)->setName('something11');

        // Save.
        $things->save();

        // Test.
        $this->assertEquals('something11', $things->getOne(0)->getName());

        // Mark to delete.
        $things->getOne(0)->defineIsToBeDeleted(true);
        $things->getOne(1)->defineIsToBeDeleted(true);

        // Delete Collection.
        $things->save();
    }

    /**
     * Test if create, update and delete on Collection works.
     *
     * @return void
     */
    public function testIfCrudOnCollectionWorks() : void
    {

        // New things.
        $things = new Things();
        $things->putRecord([
            'name' => 'something1'
        ]);
        $things->putRecord([
            'name' => 'something2'
        ]);

        // Test debug.
        $things->create(null, true);

        // Save Collection.
        $things->create();

        // Test.
        $this->assertEquals('integer', gettype($things->getOne(0)->getPk()));
        $this->assertEquals('integer', gettype($things->getOne(1)->getPk()));

        // Change.
        $things->getOne(0)->setName('something11');

        // Test debug.
        $things->update(null, true);

        // Save.
        $things->update();

        // Test.
        $this->assertEquals('something11', $things->getOne(0)->getName());

        // Mark to delete.
        $things->getOne(0)->defineIsToBeDeleted(true);
        $things->getOne(1)->defineIsToBeDeleted(true);

        // Test debug.
        $things->delete(null, true);

        // Delete Collection.
        $things->delete();
    }

    /**
     * Test if clearing Collection works.
     *
     * @return void
     */
    public function testIfClearingCollectionWorks() : void
    {

        // New things.
        $things = new Things();
        $things->putRecord([
            'name' => 'something1'
        ]);
        $things->putRecord([
            'name' => 'something2'
        ]);

        // Test.
        $this->assertEquals(2, $things->length());

        // Clear Collection.
        $things->clear();

        // Test.
        $this->assertEquals(0, $things->length());
    }

    /**
     * Test if trying to get wrong slice of records throws.
     *
     * @return void
     */
    public function testIfWrongSliceOfCollectionThrows() : void
    {

        $this->expectException(CollectionSliceNotPossibleException::class);

        // New things.
        $things = new Things();
        $things->putRecord([
            'name' => 'something1'
        ]);
        $things->putRecord([
            'name' => 'something2'
        ]);

        // Test.
        $things->get(0, 4);
    }

    /**
     * Test if trying to get wrong slice of records throws.
     *
     * @return void
     */
    public function testIfWrongReadThrows() : void
    {

        $this->expectException(CollectionCantBeReadException::class);

        // New things.
        $things = new Things();
        $things->putRecord([
            'name' => 'something1'
        ]);
        $things->putRecord([
            'name' => 'something2'
        ]);

        // Test.
        $things->read(0, 2, [ 'nonexistingField' ]);
    }
}
