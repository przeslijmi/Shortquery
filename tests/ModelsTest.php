<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\ForTests\Models\Car;
use Przeslijmi\Shortquery\ForTests\Models\Cars;
use Przeslijmi\Shortquery\ForTests\Models\Girl;
use Przeslijmi\Shortquery\ForTests\Models\Girls;
use Przeslijmi\Shortquery\ForTests\Models\Thing;
use Przeslijmi\Shortquery\ForTests\Models\Things;
use stdClass;

/**
 * Methods for testing Models created for testing :) ... .
 */
final class ModelsTest extends TestCase
{

    /**
     * Test Car instance.
     *
     * @return void
     */
    public function testCar() : void
    {

        // Create.
        $car = new Car();
        $car->setPk(1);
        $car->setPk(1);
        $car->read();
        $car->expandOneOwnerGirl();
        $car->expandOneOwnerGirlWithSnapchat();

        // Test.
        $this->assertEquals(1, $car->getOwnerGirl());
        $this->assertEquals('yes', $car->getIsFast());
        $this->assertEquals('tak', $car->getIsFast('main'));
        $this->assertEquals('Toyota', $car->getName());
        $this->assertEquals(Girl::class, get_class($car->getOneOwnerGirl()));
        $this->assertEquals(Girl::class, get_class($car->getOneOwnerGirlWithSnapchat()));
        $this->assertEquals('Adriana', $car->getOneOwnerGirl()->getName());
        $this->assertEquals('Adriana', $car->getOneOwnerGirlWithSnapchat()->getName());

        // Make changes.
        $car->defineIsAdded(false);
        $car->setOwnerGirl(2);
        $car->setOwnerGirl(2);
        $car->setIsFast('no');
        $car->setIsFast('no');
        $car->setName('AUDI');
        $car->setName('AUDI');

        // Test.
        $this->assertFalse($car->hasPrimaryKey());
        $this->assertEquals(2, $car->getOwnerGirl());
        $this->assertEquals('no', $car->getIsFast());
        $this->assertEquals('nie', $car->getIsFast('main'));
        $this->assertEquals('AUDI', $car->getName());
    }

    /**
     * Test Cars collection.
     *
     * @return void
     */
    public function testCars() : void
    {

        // Prepare.
        $cars = new Cars();
        $cars->getLogics()->addRule('pk', [ 1, 2 ]);
        $cars->read();
        $cars->expandOneOwnerGirl();
        $cars->expandOneOwnerGirlWithSnapchat();

        // Test.
        $this->assertEquals(2, count($cars->get()));

        // Take first car.
        $car = $cars->get()[0];

        // Test.
        $this->assertEquals(1, $car->getOwnerGirl());
        $this->assertEquals('yes', $car->getIsFast());
        $this->assertEquals('tak', $car->getIsFast('main'));
        $this->assertEquals('Toyota', $car->getName());
        $this->assertEquals(Girl::class, get_class($car->getOneOwnerGirl()));
        $this->assertEquals(Girl::class, get_class($car->getOneOwnerGirlWithSnapchat()));
        $this->assertEquals('Adriana', $car->getOneOwnerGirl()->getName());
        $this->assertEquals('Adriana', $car->getOneOwnerGirlWithSnapchat()->getName());
    }

    /**
     * Test empty Cars collection.
     *
     * @return void
     */
    public function testEmptyCars() : void
    {

        // Prepare.
        $cars = new Cars();
        $cars->getLogics()->addRule('pk', [ 99991, 99992 ]);
        $cars->read();
        $cars->expandOneOwnerGirl();
        $cars->expandOneOwnerGirlWithSnapchat();

        // Test.
        $this->assertEquals(0, count($cars->get()));
    }

    /**
     * Test Girl instance.
     *
     * @return void
     */
    public function testGirl() : void
    {

        // Create.
        $girl = new Girl();
        $girl->setPk(1);
        $girl->setPk(1);
        $girl->read();
        $girl->expandCars();
        $girl->expandFastCars();

        // Test.
        $this->assertEquals('Adriana', $girl->getName());
        $this->assertEquals('sc,is', $girl->getWebs());
        $this->assertEquals('snapchat,instagram', $girl->getWebs('main'));
        $this->assertEquals(Cars::class, get_class($girl->getCars()));
        $this->assertEquals(Cars::class, get_class($girl->getFastCars()));
        $this->assertEquals(2, count($girl->getCars()->get()));
        $this->assertEquals('Toyota', $girl->getCars()->get()[0]->getName());
        $this->assertEquals(1, count($girl->getFastCars()->get()));
        $this->assertEquals('Toyota', $girl->getFastCars()->get()[0]->getName());

        // Make changes.
        $girl->defineIsAdded(false);
        $girl->setName('Adrianetta');
        $girl->setName('Adrianetta');
        $girl->setWebs('fb');
        $girl->setWebs('fb');

        // Test.
        $this->assertFalse($girl->hasPrimaryKey());
        $this->assertEquals('Adrianetta', $girl->getName());
        $this->assertEquals('fb', $girl->getWebs());
        $this->assertEquals('facebook', $girl->getWebs('main'));

        // Make more changes.
        $girl->addToWebs('is');
        $this->assertEquals('instagram,facebook', $girl->getWebs('main'));
        $girl->deleteFromWebs('fb');
        $this->assertEquals('instagram', $girl->getWebs('main'));
        $girl->setWebs(null);
        $this->assertEquals('', $girl->getWebs('main'));
        $girl->addToWebs('is,fb');
        $this->assertEquals('instagram,facebook', $girl->getWebs('main'));
    }

    /**
     * Test Girls collection.
     *
     * @return void
     */
    public function testGirls() : void
    {

        // Prepare.
        $girls = new Girls();
        $girls->getLogics()->addRule('pk', [ 1, 4 ]);
        $girls->read();
        $girls->expandCars();
        $girls->expandFastCars();

        // Test.
        $this->assertEquals(2, count($girls->get()));

        // Take both girls.
        $girlA = $girls->get()[0];
        $girlB = $girls->get()[1];

        // Test girl A.
        $this->assertEquals('Adriana', $girlA->getName());
        $this->assertEquals('sc,is', $girlA->getWebs());
        $this->assertEquals('snapchat,instagram', $girlA->getWebs('main'));
        $this->assertEquals(Cars::class, get_class($girlA->getFastCars()));
        $this->assertEquals('Toyota', $girlA->getCars()->get()[0]->getName());
        $this->assertEquals('Toyota', $girlA->getFastCars()->get()[0]->getName());

        // Test girl B.
        $this->assertEquals('Makenzie', $girlB->getName());
        $this->assertEquals('sc', $girlB->getWebs());
        $this->assertEquals('snapchat', $girlB->getWebs('main'));
        $this->assertEquals(Cars::class, get_class($girlB->getFastCars()));
        $this->assertEquals(0, count($girlB->getCars()->get()));
        $this->assertEquals(0, count($girlB->getFastCars()->get()));
    }

    /**
     * Test empty Girls collection.
     *
     * @return void
     */
    public function testEmptyGirls() : void
    {

        // Prepare.
        $girls = new Girls();
        $girls->getLogics()->addRule('pk', [ 99991, 99992 ]);
        $girls->read();
        $girls->expandCars();
        $girls->expandFastCars();

        // Test.
        $this->assertEquals(0, count($girls->get()));
    }

    /**
     * Test Thing instance.
     *
     * @return void
     */
    public function testThing() : void
    {

        // Create.
        $thing = new Thing();
        $thing->setPk(1);
        $thing->setPk(1);
        $thing->read();

        // Define JSON.
        $json        = new stdClass();
        $json->data1 = true;
        $json->data2 = 5;
        $json->data3 = 'string';

        // Test.
        $this->assertEquals('something', $thing->getName());
        $this->assertEquals($json, $thing->getJsonData());

        // Enlarge JSON.
        $json->data4 = 'hello';

        // Make changes.
        $thing->defineIsAdded(false);
        $thing->setName('something else');
        $thing->setName('something else');
        $thing->setJsonData($json);
        $thing->setJsonData($json);

        // Test.
        $this->assertFalse($thing->hasPrimaryKey());
        $this->assertEquals('something else', $thing->getName());
        $this->assertEquals($json, $thing->getJsonData());
    }

    /**
     * Test Things collection.
     *
     * @return void
     */
    public function testThings() : void
    {

        // Prepare.
        $things = new Things();
        $things->getLogics()->addRule('pk', [ 1 ]);
        $things->read();

        // Define JSON.
        $json        = new stdClass();
        $json->data1 = true;
        $json->data2 = 5;
        $json->data3 = 'string';

        // Test.
        $this->assertEquals(1, count($things->get()));

        // Take both girls.
        $thing = $things->get()[0];

        // Test girl A.
        $this->assertEquals('something', $thing->getName());
        $this->assertEquals($json, $thing->getJsonData());
    }
}
