<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Engine\MySql\Queries\UpdateQuery;
use Przeslijmi\Shortquery\Exceptions\Items\RuleCreationFopException;
use Przeslijmi\Shortquery\ForTests\Models\Car;
use Przeslijmi\Shortquery\ForTests\Models\Cars;
use Przeslijmi\Shortquery\ForTests\Models\Core\CarModel;
use Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel;

/**
 * Methods for testing UpdateQuery from MySql Engine.
 */
final class UpdateTest extends TestCase
{

    /**
     * Test if short track of changing nothing will work.
     *
     * @return void
     */
    public function testIfUpdatingNothingShortTrackWillWork() : void
    {

        // Prepare car.
        $car = new Car();
        $car->setPk(1);
        $car->read();

        // Prepare.
        $update = new UpdateQuery(new CarModel());
        $update->addInstance($car);

        // Test.
        $this->assertEquals('', $update->toString());
    }

    /**
     * Test if short track of changing nothing will work.
     *
     * @return void
     */
    public function testIfUpdatingWorks() : void
    {

        // Prepare car.
        $car = new Car();
        $car->setPk(5);
        $car->read();
        $car->setName('BMW ...');

        // Change.
        $update = new UpdateQuery(new CarModel());
        $update->addInstance($car);
        $update->call();

        // Test car.
        $testCar = new Car();
        $testCar->setPk(5);
        $testCar->read();
        $this->assertEquals('BMW ...', $testCar->getName());

        // Rechange back.
        $car->setName('BMW');

        // Change again.
        $update = new UpdateQuery(new CarModel());
        $update->addInstance($car);
        $update->call();

        // Empty instances.
        $update->clearInstances();

        // Test.
        $this->assertEquals(0, count($update->getInstances()));

        // Add two instances.
        $update->addInstances([ $car, $car ]);

        // Test.
        $this->assertEquals(2, count($update->getInstances()));
    }

    /**
     * Test if creating query with unproper rule will throw.
     *
     * @return void
     */
    public function testIfCreatingQueryWithWrongRuleThrows() : void
    {

        // Create.
        $update = new UpdateQuery(new CarModel());

        // Prepare.
        $this->expectException(RuleCreationFopException::class);

        // Test.
        $update->addRule(null);
    }
}
