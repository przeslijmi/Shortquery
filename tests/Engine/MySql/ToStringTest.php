<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Engine\MySql\ToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\LogicsToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\RuleToString;
use Przeslijmi\Shortquery\Exceptions\Engines\MySql\ToStringFopException;
use Przeslijmi\Shortquery\Exceptions\Items\FuncToStringFopException;
use Przeslijmi\Shortquery\Exceptions\Items\LogicsToStringWrongComponentsException;
use Przeslijmi\Shortquery\Items\Comp;
use Przeslijmi\Shortquery\Items\FalseVal;
use Przeslijmi\Shortquery\Items\Field;
use Przeslijmi\Shortquery\Items\Func;
use Przeslijmi\Shortquery\Items\IntVal;
use Przeslijmi\Shortquery\Items\LogicAnd;
use Przeslijmi\Shortquery\Items\LogicOr;
use Przeslijmi\Shortquery\Items\NullVal;
use Przeslijmi\Shortquery\Items\Rule;
use Przeslijmi\Shortquery\Items\TrueVal;
use Przeslijmi\Shortquery\Items\Val;
use Przeslijmi\Shortquery\Items\Vals;
use stdClass;

/**
 * Methods for testing CustomQuery from MySql Engine.
 */
final class ToStringTest extends TestCase
{

    /**
     * Data provider for to-string tests.
     *
     * @return array
     */
    public function dataProviderForWorkingTest() : array
    {

        return [
            [
                Func::factory('concat', [
                    new Val('val'),
                    new IntVal(5),
                    new NullVal(),
                    new Vals([ 'val1', 'val2' ]),
                    Func::factory('concat', [ '5', '10' ]),
                    new Field('fieldName'),
                    new TrueVal(),
                    new FalseVal()
                ]),
                'CONCAT(\'val\', 5, NULL, \'val1\', \'val2\', CONCAT(\'5\', \'10\'), `fieldName`, TRUE, FALSE)'
            ],
            [
                Func::factory('between', [ 5, 10 ]),
                ' BETWEEN 5 AND 10 '
            ],
            [
                new Rule(new TrueVal(), new Comp('neq'), Func::factory('between', [ 5, 10 ])),
                'TRUE NOT BETWEEN 5 AND 10 ',
            ],
            [
                new Rule(new TrueVal(), new Comp('neq'), Func::factory('concat', [ '5', '10' ])),
                'TRUE!=CONCAT(\'5\', \'10\')',
            ],
            [
                new Rule(new TrueVal(), new Comp('neq'), Func::factory('count', [ '5' ])),
                'TRUE!=COUNT(\'5\')',
            ],
            [
                new Rule(new TrueVal(), new Comp('neq'), Func::factory('count', [])),
                'TRUE!=COUNT(*)',
            ],
            [
                new Rule(new TrueVal(), new Comp('neq'), Func::factory('dateDiffDays', [ 'start', 'end' ])),
                'TRUE!=DATEDIFF(\'start\', \'end\')',
            ],
            [
                new Rule(Func::factory('inset', [ 'needle', 'set' ]), new Comp('neq'), new TrueVal()),
                'FIND_IN_SET( \'needle\', \'set\' ) !=TRUE',
            ],
            [
                new Rule(new TrueVal(), new Comp('neq'), Func::factory('in', [ 'wordA', 'wordB' ])),
                'TRUE NOT IN (\'wordA\', \'wordB\')',
            ],
            [
                new Rule(new TrueVal(), new Comp('neq'), Func::factory('localfunc', [ 'MYFUNC', 'wordB' ])),
                'TRUE!= MYFUNC (\'wordB\')',
            ],
            [
                new Rule(new TrueVal(), new Comp('neq'), Func::factory('min', [ 'value' ])),
                'TRUE!=MIN(\'value\')',
            ],
            [
                new Rule(new TrueVal(), new Comp('neq'), Func::factory('sum', [ 'value' ])),
                'TRUE!=SUM(\'value\')',
            ],
            [ new Comp('eq'), '=' ],
            [ new Comp('neq'), '!=' ],
            [ new Comp('gt'), '>' ],
            [ new Comp('geq'), '>=' ],
            [ new Comp('leq'), '<=' ],
            [ new Comp('lt'), '<' ],
            [ new Comp('is'), ' IS ' ],
            [ new Comp('nis'), ' IS NOT ' ],
            [ new Comp('lk'), ' LIKE ' ],
            [ new Comp('nlk'), ' NOT LIKE ' ],
            [ ( new Comp('nlk') )->setSilent(true), '' ],
            [ new FalseVal(), 'FALSE' ],
            [
                new Field('name'),
                '`name` AS `alias`',
                'alias',
            ],
            [
                new Field('name', 'table'),
                '`table`.`name` AS `alias`',
                'alias',
            ],
            [ new Field('*'), '*' ],
            [ new IntVal(110), '110' ],
            [
                [
                    new LogicOr(
                        new Rule(new Field('name'), new Comp('eq'), new Val('john')),
                        new Rule(new Field('age'), new Comp('lt'), new Val('25'))
                    ),
                    new LogicAnd(
                        new Rule(new Field('name'), new Comp('eq'), new Val('john')),
                        new Rule(new Field('age'), new Comp('lt'), new Val('25'))
                    ),
                ],
                '(`name`=\'john\' OR `age`<\'25\') AND (`name`=\'john\' AND `age`<\'25\')',
            ],
            [
                new LogicAnd(
                    new Rule(new Field('name'), new Comp('eq'), new Val('john')),
                    new Rule(new Field('age'), new Comp('lt'), new Val('25'))
                ),
                '(`name`=\'john\' AND `age`<\'25\')',
            ],
            [ new NullVal(), 'NULL' ],
            [
                new Rule(new Val('john'), new Comp('eq'), new Field('name')),
                '\'john\'=`name`',
            ],
            [
                new Rule(new Field('name'), new Comp('neq'), new Val('john')),
                '`name`!=\'john\'',
            ],
            [
                new Rule(new IntVal(10), new Comp('eq'), new NullVal()),
                '10 IS NULL',
            ],
            [
                new Rule(new IntVal(10), new Comp('neq'), new NullVal()),
                '10 IS NOT NULL',
            ],
            [
                new Rule(new NullVal(), new Comp('neq'), new IntVal(10)),
                'NULL!=10',
            ],
            [
                new Rule(new Vals([ 'john' ]), new Comp('eq'), Func::factory('concat', [ 'jo', 'hn' ])),
                '\'john\'=CONCAT(\'jo\', \'hn\')',
            ],
            [
                new Rule(Func::factory('concat', [ 'jo', 'hn' ]), new Comp('neq'), new Vals([ 'john' ])),
                'CONCAT(\'jo\', \'hn\')!=\'john\'',
            ],
            [
                new Rule(new TrueVal(), new Comp('eq'), new FalseVal()),
                'TRUE=FALSE',
            ],
            [
                new Rule(new FalseVal(), new Comp('neq'), new TrueVal()),
                'FALSE!=TRUE',
            ],
            [ new TrueVal(), 'TRUE' ],
            [
                new Val('aaaa'),
                '\'aaaa\' AS `alias`',
                'alias',
            ],
            [
                new Vals([ '513', 513, 'aaa' ]),
                '\'513\', \'513\', \'aaa\'',
            ],
        ];
    }

    /**
     * Tests if `toString` methods works.
     *
     * @param array|AnyItem $item   Item to be tested (array for Logics).
     * @param string        $output What output to expect.
     * @param null|string   $alias  Optional, null. If given - alias will be set and tested.
     *
     * @return void
     *
     * @dataProvider dataProviderForWorkingTest
     */
    public function testIfToStringWorks($item, string $output, ?string $alias = null) : void
    {

        // Add alias (if needed).
        if ($alias !== null) {
            $item->setAlias($alias);
        }

        // Convert to string.
        $string = ToString::convert($item);

        // Test.
        $this->assertEquals($output, $string);
    }

    /**
     * Tast if converting fake logic throws.
     *
     * @return void
     */
    public function testIfConvertingFakeLogicsToStringThrows() : void
    {

        // Prepare.
        $this->expectException(LogicsToStringWrongComponentsException::class);

        // Call.
        ToString::convert([ 'notALogicsArray' ]);
    }

    /**
     * Test if converting set of logics to WHERE string.
     *
     * @return void
     */
    public function testIfConvertingSetOfLogicsToWhereStringWorks() : void
    {

        // Prepare.
        $obj = new LogicsToString([
            new LogicOr(
                new Rule(new Field('name'), new Comp('eq'), new Val('john')),
                new Rule(new Field('age'), new Comp('lt'), new Val('25'))
            ),
            new LogicAnd(
                new Rule(new Field('name'), new Comp('eq'), new Val('john')),
                new Rule(new Field('age'), new Comp('lt'), new Val('25'))
            ),
        ]);

        // Define result.
        $result = ' WHERE (`name`=\'john\' OR `age`<\'25\') AND (`name`=\'john\' AND `age`<\'25\')';

        // Call.
        $this->assertEquals($result, $obj->toWhereString());
    }

    /**
     * Test if converting non-existing func to string will throw.
     *
     * @return void
     */
    public function testIfConvertingNonExistingFuncWillThrow() : void
    {

        // Create func.
        $func = Func::factory('nonExistingFunc', [ 'aa', 'bb' ]);

        // Prepare.
        $this->expectException(FuncToStringFopException::class);

        // Test.
        ( new FuncToString($func) )->toString();
    }

    /**
     * Test if giving not equal (to ordered) number of arguments will throw.
     *
     * @return void
     */
    public function testIfGivingNotEqualNumberOfArgumentsThrows() : void
    {

        // Create between with only one argument - when exactly two are needed.
        $func = Func::factory('between', [ 2 ]);

        // Prepare.
        $this->expectException(FuncToStringFopException::class);

        // Test.
        ( new FuncToString($func) )->toString();
    }

    /**
     * Test if giving not equal (to ordered) number of arguments will throw.
     *
     * @return void
     */
    public function testIfGivingTooLittleArgumentsThrows() : void
    {

        // Create between with only one argument - when exactly two are needed.
        $func = Func::factory('concat', [ ]);

        // Prepare.
        $this->expectException(FuncToStringFopException::class);

        // Test.
        ( new FuncToString($func) )->toString();
    }

    /**
     * Test if giving func with wrong comp method will throw.
     *
     * @return void
     */
    public function testIfStringinFuncWithWrongPairCompMethodThrows() : void
    {

        // Create rule with incorrect comp for func.
        $rule = new Rule(new TrueVal(), new Comp('leq'), Func::factory('in', [ 'wordA', 'wordB' ]));

        // Prepare.
        $this->expectException(FuncToStringFopException::class);

        // Test.
        ( new RuleToString($rule) )->toString();
    }

    /**
     * Test if converting to string inproper object will throw.
     *
     * @return void
     */
    public function testIfConvertingInproperObjectWillThrow() : void
    {

        // Prepare.
        $this->expectException(ToStringFopException::class);

        // Test.
        ToString::convert(new stdClass());
    }

    /**
     * Test if converting to string scalar will throw.
     *
     * @return void
     */
    public function testIfConvertingScalarWillThrow() : void
    {

        // Prepare.
        $this->expectException(ToStringFopException::class);

        // Test.
        ToString::convert('test');
    }
}
