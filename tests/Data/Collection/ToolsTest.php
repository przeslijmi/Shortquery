<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Collection;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\ForTests\Models\Girls;
use Przeslijmi\Shortquery\ForTests\Models\Cars;

/**
 * Methods for testing Collections of Shortquery class.
 */
final class ToolsTest extends TestCase
{

    /**
     * Test if `->makeContentAnalogousToArray()` works properly (first).
     *
     * @return void
     */
    public function testIfMakingContentAnalogousToArrayWorks1() : void
    {

        $girls = new Girls();
        $girls->getLogics()->addRule('pk', [ 1, 2, 3 ]);
        $girls->read();

        // Create changed content.
        $newGirls = [
            [
                'pk'   => 1,
                'name' => 'Kylie test',
                'webs' => 'fb',
            ],
            [
                'pk'   => 2,
                'name' => 'Diamond test',
                'webs' => 'pt',
            ],
            [
                'pk'   => 3,
                'name' => 'Makenzie test',
                'webs' => 'sc',
            ],
            [
                'name' => 'New girl',
                'webs' => null,
            ],
        ];

        // Change collection.
        $girls->makeContentAnalogousToArray($newGirls);

        // Tests.
        $this->assertEquals($newGirls[0]['name'], $girls->getOne(0)->getName());
        $this->assertEquals($newGirls[1]['name'], $girls->getOne(1)->getName());
        $this->assertEquals($newGirls[2]['name'], $girls->getOne(2)->getName());
        $this->assertEquals($newGirls[3]['name'], $girls->getOne(3)->getName());
        $this->assertEquals($newGirls[0]['webs'], $girls->getOne(0)->getWebs());
        $this->assertEquals($newGirls[1]['webs'], $girls->getOne(1)->getWebs());
        $this->assertEquals($newGirls[2]['webs'], $girls->getOne(2)->getWebs());
        $this->assertEquals($newGirls[3]['webs'], $girls->getOne(3)->getWebs());

        // Get by added.
        $byAdded = $girls->getByAdded();

        // Tests.
        $this->assertEquals(3, count($byAdded[0]));
        $this->assertEquals(1, count($byAdded[1]));
    }

    /**
     * Test if `->makeContentAnalogousToArray()` works properly (second).
     *
     * @return void
     */
    public function testIfMakingContentAnalogousToArrayWorks2() : void
    {

        $girls = new Girls();
        $girls->getLogics()->addRule('pk', [ 1, 2, 3 ]);
        $girls->read();

        // Create changed content.
        $newGirls = [
            [
                'pk'   => 1,
                'name' => 'Kylie test',
                'webs' => 'fb',
            ],
        ];

        // Change collection.
        $girls->makeContentAnalogousToArray($newGirls);

        // Tests.
        $this->assertEquals($newGirls[0]['name'], $girls->getOne(0)->getName());
        $this->assertEquals($newGirls[0]['webs'], $girls->getOne(0)->getWebs());
        $this->assertEquals(3, $girls->length());
        $this->assertEquals(1, $girls->lengthReal());
        $this->assertFalse($girls->getOne(0)->grabIsToBeDeleted());
        $this->assertTrue($girls->getOne(1)->grabIsToBeDeleted());
        $this->assertTrue($girls->getOne(2)->grabIsToBeDeleted());

        // Tests.
        $this->assertEquals(2, count($girls->getByToBeDeleted()));
    }

    /**
     * Test if `->makeSplittedContentAnalogousToArray()` works properly (second).
     *
     * @return void
     */
    public function testIfMakingSplittedContentAnalogousToArrayWorks2() : void
    {

        // Get cars.
        $cars = new Cars();
        $cars->getLogics()->addRule('pk', [ 1, 2, 3 ]);
        $cars->read();

        // Get field.
        $field = $cars->getModel()->getFieldByName('is_fast');

        // Create changed content.
        $newCars = [
            'yes' => [
                [
                    'pk'         => 1,
                    'owner_girl' => 1,
                    'name'       => 'Toyota 123',
                ],
                [
                    'pk'         => 3,
                    'owner_girl' => 2,
                    'name'       => 'Opel 456',
                ],
            ],
            'no' => [
                [
                    'pk'         => 2,
                    'owner_girl' => 1,
                    'name'       => 'Nissan 789',
                ],
            ],
        ];

        // Change collection.
        $cars->makeSplittedContentsAnalogousToArray($field, $newCars);

        // Tests.
        $this->assertEquals($newCars['yes'][0]['name'], $cars->getOne(0)->getName());
        $this->assertEquals($newCars['yes'][1]['name'], $cars->getOne(1)->getName());
        $this->assertEquals($newCars['no'][0]['name'], $cars->getOne(2)->getName());
    }

    /**
     * Test if `->makeSplittedContentAnalogousToArray()` works properly (third).
     *
     * @return void
     */
    public function testIfMakingSplittedContentAnalogousToArrayWorks3() : void
    {

        // Get cars.
        $cars = new Cars();
        $cars->getLogics()->addRule('pk', [ 1, 2, 3 ]);
        $cars->read();

        // Get field.
        $field = $cars->getModel()->getFieldByName('is_fast');

        // Create changed content.
        $newCars = [
            'yes' => [
                [
                    'pk'         => 1,
                    'owner_girl' => 1,
                    'name'       => 'Toyota 123',
                ],
                [
                    'pk'         => 3,
                    'owner_girl' => 2,
                    'name'       => 'Opel 456',
                ],
            ],
        ];

        // Change collection.
        $cars->makeSplittedContentsAnalogousToArray($field, $newCars);

        // Tests.
        $this->assertEquals($newCars['yes'][0]['name'], $cars->getOne(0)->getName());
        $this->assertEquals($newCars['yes'][1]['name'], $cars->getOne(1)->getName());
        $this->assertEquals(3, $cars->length());
        $this->assertEquals(2, $cars->lengthReal());
    }

    /**
     * Test if splitting collection by field works.
     *
     * @return void
     */
    public function testIfSplittingByFieldWorks() : void
    {

        // Lvd.
        $cars = new Cars();
        $cars->read();

        // Make splitting.
        $bySpeed = $cars->splitByField($cars->getModel()->getFieldByName('is_fast'));

        // Tests.
        $this->assertEquals(2, count($bySpeed));
        $this->assertTrue(in_array('yes', array_keys($bySpeed)));
        $this->assertTrue(in_array('no', array_keys($bySpeed)));
        $this->assertEquals(3, count($bySpeed['yes']->get()));
        $this->assertEquals(2, count($bySpeed['no']->get()));
    }
}
