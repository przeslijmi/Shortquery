<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Exceptions\Data\InstanceConstructionFopException;
use Przeslijmi\Shortquery\Exceptions\Data\InstanceCreationWrongParamException;
use Przeslijmi\Shortquery\ForTests\Models\Girls;
use Przeslijmi\Shortquery\Tools\InstancesFactory;

/**
 * Methods for testing Items.
 */
final class InstancesFactoryTest extends TestCase
{

    /**
     * Test if InstancesFactory throws on wrong arguments.
     *
     * @return void
     */
    public function testIfThrowsOnWrongArguments() : void
    {

        // Prepare.
        $this->expectException(InstanceCreationWrongParamException::class);

        // Test.
        InstancesFactory::fromArray(true, []);
    }

    /**
     * Test if InstancesFactory throws on nonexisting collection class.
     *
     * @return void
     */
    public function testIfThrowsOnNonexistsingCollectionClass() : void
    {

        // Prepare.
        $this->expectException(InstanceConstructionFopException::class);

        // Test.
        InstancesFactory::fromArray('NonexistingClass', []);
    }

    /**
     * Test if InstancesFactory throws on wrong props.
     *
     * @return void
     */
    public function testIfThrowsOnWrongProps() : void
    {

        // Prepare.
        $this->expectException(InstanceConstructionFopException::class);

        // Test.
        InstancesFactory::fromArray(Girls::class, [ 5, 5, 5 ]);
    }

    /**
     * Test if InstancesFactory throws on wrong collection class.
     *
     * @return void
     */
    public function testIfThrowsOnWrongCollectionClass() : void
    {

        // Prepare.
        $this->expectException(InstanceConstructionFopException::class);

        // Test.
        InstancesFactory::fromArray(InstanceConstructionFopException::class, [ 'pk' => '1' ]);
    }
}
