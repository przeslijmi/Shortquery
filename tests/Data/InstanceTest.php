<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\ForTests\Models\Girl;
use Przeslijmi\Shortquery\ForTests\CreatorStarter;
use Przeslijmi\Shortquery\Exceptions\Items\FieldValueUnaccesibleException;
use Przeslijmi\Shortquery\Exceptions\Items\FieldDictValueUnaccesibleException;

/**
 * Methods for testing Items of Shortquery class.
 */
final class InstanceTest extends TestCase
{

    /**
     * Test if creator works properly.
     *
     * @return void
     */
    public function testModelCreator() : void
    {

        $md = new CreatorStarter();
        $md->run('schemaForTesting.php');

        $this->assertEquals(1, 1);
    }

    /**
     * Test if Girl Item can be properly read.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testIfReadingGirlItemByPkWorks() : void
    {

        // Create new Item and call it to be read.
        $girl = new Girl();
        $girl->setPk(1);
        $girl->expandCars();
        $girl->expandFastCars();
        $girl->read();

        // Tests.
        $this->assertEquals(1, $girl->getPk());
        $this->assertEquals(1, $girl->grabPkValue());
        $this->assertEquals(1, $girl->grabFieldValue('pk'));
        $this->assertEquals('pinterest,facebook', $girl->grabMultiDictFieldValue('webs', 'main', 'pt,fb'));
        $this->assertEquals('pinterest', $girl->grabDictFieldValue('webs', 'main', 'pt'));
        $this->assertEquals(true, $girl->grabIsAdded());
        $this->assertEquals('pk', $girl->grabPkName());
        $this->assertEquals('Adriana', $girl->getName());
        $this->assertEquals(2, count($girl->getCars()->get()));
        $this->assertEquals(1, count($girl->getFastCars()->get()));
    }

    /**
     * Test if Girl Item can be properly read.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testIfReadingGirlItemByNameWorks() : void
    {

        // Create new Item and call it to be read.
        $girl = new Girl();
        $girl->setName('Adriana');
        $girl->read();

        // Tests.
        $this->assertEquals(1, $girl->getPk());
        $this->assertEquals(1, $girl->grabPkValue());
        $this->assertEquals(1, $girl->grabFieldValue('pk'));
        $this->assertEquals('pinterest,facebook', $girl->grabMultiDictFieldValue('webs', 'main', 'pt,fb'));
        $this->assertEquals('pinterest', $girl->grabDictFieldValue('webs', 'main', 'pt'));
        $this->assertEquals(true, $girl->grabIsAdded());
        $this->assertEquals('pk', $girl->grabPkName());
        $this->assertEquals('Adriana', $girl->getName());
    }

    /**
     * Test if trying to read nonexisting Field throws.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testIfTryingToReadNonexistingFieldThrows() : void
    {

        $this->expectException(FieldValueUnaccesibleException::class);

        // Create new Item and call it to be read.
        $girl = new Girl();
        $girl->setPk(1);
        $girl->read();
        $girl->grabFieldValue('nonexistingField');
    }

    /**
     * Test if trying to get nonexisting dictionary multi in Field throws.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testIfTryingToReadNonexistingDictMultiThrows() : void
    {

        $this->expectException(FieldDictValueUnaccesibleException::class);

        // Create new Item and call it to be read.
        $girl = new Girl();
        $girl->setPk(1);
        $girl->read();
        $girl->grabMultiDictFieldValue('webs', 'nonexistingDict', 'pt');
    }

    /**
     * Test if trying to get nonexisting dictionary multi key in Field throws.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testIfTryingToReadNonexistingDictMultiKeyThrows() : void
    {

        $this->expectException(FieldDictValueUnaccesibleException::class);

        // Create new Item and call it to be read.
        $girl = new Girl();
        $girl->setPk(1);
        $girl->read();
        $girl->grabMultiDictFieldValue('webs', 'main', 'nonexistingKey');
    }

    /**
     * Test if trying to get nonexisting dictionary in Field throws.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testIfTryingToReadNonexistingDictThrows() : void
    {

        $this->expectException(FieldDictValueUnaccesibleException::class);

        // Create new Item and call it to be read.
        $girl = new Girl();
        $girl->setPk(1);
        $girl->read();
        $girl->grabDictFieldValue('webs', 'nonexistingDict', 'pt');
    }

    /**
     * Test if trying to get nonexisting dictionary key in Field throws.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testIfTryingToReadNonexistingDictKeyThrows() : void
    {

        $this->expectException(FieldDictValueUnaccesibleException::class);

        // Create new Item and call it to be read.
        $girl = new Girl();
        $girl->setPk(1);
        $girl->read();
        $girl->grabDictFieldValue('webs', 'main', 'nonexistingKey');
    }

    /**
     * Test if creating and deleting record works.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testIfCreatingRecordWorks() : void
    {

        // Create girl.
        $girl = new Girl();
        $girl->setName('angelpolikarpova');
        $girl->create();

        // Check if PK exists for girl.
        $this->assertEquals('integer', gettype($girl->getPk()));

        // Save PK.
        $firstPk = $girl->getPk();

        // Now delete.
        $girl->delete();

        // And create again.
        $girl->createIfNotExists();

        // Check if now exists and has different PK.
        $this->assertEquals('integer', gettype($girl->getPk()));
        $this->assertNotEquals($firstPk, $girl->getPk());

        // Now delete again finally.
        $girl->delete();
    }

    /**
     * Test if deleting nonadded record works.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testIfDeletingNoncreatedRecordWorks() : void
    {

        // Create girl.
        $girl = new Girl();
        $girl->setName('presleyelise');

        // Test.
        $this->assertEquals($girl, $girl->delete());
    }

    /**
     * Test if saving with cration and update works.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testIfSaveWorks() : void
    {

        // Create girl.
        $girl = new Girl();
        $girl->setName('kalocsay.niki');
        $girl->save();
        $pk = $girl->getPk();

        // Test.
        $this->assertEquals('integer', gettype($girl->getPk()));

        // Create new instance to read
        $girl = new Girl();
        $girl->setPk($pk);
        $girl->read();
        $girl->setName('kalocsay.niki2');
        $girl->save();

        // Test.
        $this->assertEquals('kalocsay.niki2', $girl->getName());

        // Now delete.
        $girl->delete();
    }

    /**
     * Test if converting Instance to string works.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testIfToStringFromAddedWorks() : void
    {

        // Create girl.
        $girl = new Girl();
        $girl->setPk(1);
        $girl->read();

        // Expected string.
        $expectedString = 'pk: 1
name: Adriana
webs: is
';

        // Test.
        $this->assertEquals($expectedString, $girl->toString());
    }

    /**
     * Test if converting Instance to string works.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testIfToStringFromNonaddedWorks() : void
    {

        // Create girl.
        $girl = new Girl();
        $girl->setName('test');

        // Expected string.
        $expectedString = 'name: test
';

        // Test.
        $this->assertEquals($expectedString, $girl->toString());
    }
}
