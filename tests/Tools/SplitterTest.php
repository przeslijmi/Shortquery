<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Tools\Splitter;

/**
 * Methods for testing Items.
 */
final class SplitterTest extends TestCase
{

    /**
     * Test if Splitter works.
     *
     * @return void
     */
    public function testIfWorks() : void
    {

        // Lvd.
        $expected = [
            [
                'text' => '<world>',
                'start' => 6,
            ],
            [
                'text' => '<This parts are  important>',
                'start' => 14,
            ],
            [
                'text' => '<but it go < deeper > and < < deeperer > > >',
                'start' => 44,
            ],
        ];

        // Create.
        $toSplit  = 'Hello <world> <This parts are \> important> <but it go < deeper > and < < deeperer > > >.';
        $splitter = new Splitter($toSplit, '<', [ '>' ], '<', '>');
        $result   = $splitter->split();

        // Test.
        $this->assertEquals($expected, $result);
    }
}
