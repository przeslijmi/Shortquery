<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Engine\MySql\Queries\DeleteQuery;
use Przeslijmi\Shortquery\Engine\MySql\Queries\InsertQuery;
use Przeslijmi\Shortquery\Engine\MySql\Queries\SelectQuery;
use Przeslijmi\Shortquery\ForTests\Models\Car;
use Przeslijmi\Shortquery\ForTests\Models\Core\CarModel;

/**
 * Methods for testing DeleteQuery from MySql Engine.
 */
final class DeleteTest extends TestCase
{

    /**
     * Test if short track of changing nothing will work.
     *
     * @return void
     */
    public function testIfDeletionWillWork() : void
    {

        // Prepare car.
        $car = new Car();
        $car->setName('temp');

        // Prepare adding.
        $insert = new InsertQuery(new CarModel());
        $insert->addInstance($car);
        $insert->call();

        // Test adding.
        $this->assertEquals('integer', gettype($car->getPk()));

        // Prepare deletion.
        $update = new DeleteQuery(new CarModel());
        $update->addRule('name', 'temp');
        $update->call();

        // Recheck presence.
        $select = new SelectQuery(new CarModel());
        $select->addRule('name', 'temp');

        // Test.
        $this->assertEquals(0, count($select->read()));
    }
}
