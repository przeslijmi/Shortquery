<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use Exception;
use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\CacheByKey;
use Przeslijmi\Shortquery\Exceptions\Data\RecordAlreadyTakenOutFromCacheByKey;
use Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel;

/**
 * Methods for testing CacheByKey tool.
 */
final class CacheByKeyTest extends TestCase
{

    /**
     * Test if Starting Creator without overwriting works.
     *
     * @return void
     */
    public function testReadingCache() : void
    {

        // Create cache.
        $cache = new CacheByKey('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel');
        $cache->prepare();

        // Test.
        $this->assertInstanceOf('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel', $cache->getModel());
        $this->assertEquals('Adriana', $cache->get(1)->getName());
        $this->assertInstanceOf('Przeslijmi\Shortquery\Engine\MySql\Queries\SelectQuery', $cache->getSelect());

        // Count, take out one, count again.
        $firstCount = count($cache->getNonTakenOutKeys());
        $cache->getOnce(1);
        $secondCount = count($cache->getNonTakenOutKeys());

        // Should be less by one piece.
        $this->assertEquals(( $firstCount - 1 ), $secondCount);
    }

    /**
     * Test if Starting Creator without overwriting basig on field other then PK works.
     *
     * @return void
     */
    public function testReadingCacheCreatedOnNonPkField() : void
    {

        // Create cache.
        $cache = new CacheByKey('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel', 'name');
        $cache->prepare();

        // Test.
        $this->assertInstanceOf('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel', $cache->getModel());
        $this->assertEquals('Adriana', $cache->get('Adriana')->getName());
        $this->assertInstanceOf('Przeslijmi\Shortquery\Engine\MySql\Queries\SelectQuery', $cache->getSelect());

        // Count, take out one, count again.
        $firstCount = count($cache->getNonTakenOutKeys());
        $cache->getOnce('Adriana');
        $secondCount = count($cache->getNonTakenOutKeys());

        // Should be less by one piece.
        $this->assertEquals(( $firstCount - 1 ), $secondCount);
    }

    /**
     * Test if Starting Creator without overwriting works.
     *
     * @return void
     */
    public function testReadingEmpyInstanceFromCache() : void
    {

        // Create cache.
        $cache = new CacheByKey('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel');
        $cache->prepare();

        // Test.
        $this->assertEquals(null, $cache->get(99999)->getName());
    }

    /**
     * Test if taking out one element twice will throw.
     *
     * @return void
     */
    public function testIfTakingOutTwiceThrows() : void
    {

        // Prepare.
        $this->expectException(RecordAlreadyTakenOutFromCacheByKey::class);

        // Create cache.
        $cache = new CacheByKey('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel');
        $cache->prepare();

        // Take out twice the same record.
        $cache->getOnce(1);
        $cache->getOnce(1);
    }

    /**
     * Test if taking out element that never existed throws.
     *
     * @return void
     */
    public function testIfTakingNonexistingElementThrows() : void
    {

        // Prepare.
        $this->expectException(Exception::class);

        // Create cache.
        $cache = new CacheByKey('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel');
        $cache->prepare();

        // Take out nonexisting record.
        $cache->get(99999, true);
    }

    /**
     * Test if taking out element that never existed throws.
     *
     * @return void
     */
    public function testIfTakingNonexistingElementWorks() : void
    {

        // Create cache.
        $cache = new CacheByKey('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel');
        $cache->prepare();

        // Take out nonexisting record.
        $girl = $cache->get(99999);

        // Test.
        $this->assertEquals(99999, $girl->getPk());
    }

    /**
     * Test if taking out element that never existed throws from cache based on other field works.
     *
     * @return void
     */
    public function testIfTakingNonexistingElementFromCacheCreatedOnNonPkFieldWorks() : void
    {

        // Create cache.
        $cache = new CacheByKey('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel', 'name');
        $cache->prepare();

        // Take out nonexisting record.
        $girl = $cache->get('Karolina');

        // Test.
        $this->assertEquals('Karolina', $girl->getName());
    }

    /**
     * Test marking as used - and getting nonused works.
     *
     * @return void
     */
    public function testIfUsedWorks() : void
    {

        // Create cache.
        $cache = new CacheByKey('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel');
        $cache->prepare();

        // Count and mark used.
        $preCount = count($cache->getNonUsedKeys());
        $cache->markUsed(1);
        $postCount = count($cache->getNonUsedKeys());

        // Test.
        $this->assertEquals(( $postCount + 1 ), $preCount);
    }

    /**
     * Test if cache with relations works properly.
     *
     * @return void
     */
    public function testItCacheWithRelationWorks() : void
    {

        // Create cache.
        $cache = new CacheByKey('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel');
        $cache->addChildren('cars');
        $cache->prepare();

        // Get element.
        $girl = $cache->getOnce(1);

        // Test.
        $this->assertEquals('Adriana', $girl->getName());
        $this->assertEquals(2, $girl->getCars()->length());
    }
}
