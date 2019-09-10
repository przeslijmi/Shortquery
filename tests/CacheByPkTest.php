<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\CacheByPk;
use Przeslijmi\Shortquery\Exceptions\Data\RecordAlreadyTakenOutFromCacheByPk;
use Przeslijmi\Shortquery\ForTests\CreatorStarter;
use Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel;

/**
 * Methods for testing CacheByPk tool.
 */
final class CacheByPkTest extends TestCase
{

    /**
     * Start model Creator.
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
     * Test if Starting Creator without overwriting works.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testReadingCache() : void
    {

        // Create cache.
        $cache = new CacheByPk('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel');
        $cache->prepare();

        // Test.
        $this->assertInstanceOf('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel', $cache->getModel());
        $this->assertEquals('Adriana', $cache->get(1)->getName());
        $this->assertInstanceOf('Przeslijmi\Shortquery\Engine\MySql\Queries\SelectQuery', $cache->getSelect());

        // Count, take out one, count again.
        $firstCount = count($cache->getNonUsedPks());
        $cache->getOnce(1);
        $secondCount = count($cache->getNonUsedPks());

        // Should be less by one piece.
        $this->assertEquals(( $firstCount - 1 ), $secondCount);
    }

    /**
     * Test if Starting Creator without overwriting works.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testReadingEmpyInstanceFromCache() : void
    {

        // Create cache.
        $cache = new CacheByPk('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel');
        $cache->prepare();

        // Test.
        $this->assertEquals(null, $cache->get(99999)->getName());
    }

    /**
     * Test if taking out one element twice will throw.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testIfTakingOutTwiceThrows() : void
    {

        $this->expectException(RecordAlreadyTakenOutFromCacheByPk::class);

        // Create cache.
        $cache = new CacheByPk('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel');
        $cache->prepare();

        // Take out twice the same record.
        $cache->getOnce(1);
        $cache->getOnce(1);
    }
}
