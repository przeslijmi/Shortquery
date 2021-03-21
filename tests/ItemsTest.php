<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Exceptions\Items\CompCreationFopException;
use Przeslijmi\Shortquery\Exceptions\Items\FuncItemOtosetException;
use Przeslijmi\Shortquery\Exceptions\Items\FuncWrongComponentsException;
use Przeslijmi\Shortquery\Exceptions\Items\LogicWrongComponentsException;
use Przeslijmi\Shortquery\Exceptions\Items\RuleCreationFopException;
use Przeslijmi\Shortquery\ForTests\Models\Girls;
use Przeslijmi\Shortquery\Items\Comp;
use Przeslijmi\Shortquery\Items\ContentItem;
use Przeslijmi\Shortquery\Items\FalseVal;
use Przeslijmi\Shortquery\Items\Field;
use Przeslijmi\Shortquery\Items\Func;
use Przeslijmi\Shortquery\Items\IntVal;
use Przeslijmi\Shortquery\Items\ItemsFactory;
use Przeslijmi\Shortquery\Items\LogicAnd;
use Przeslijmi\Shortquery\Items\LogicItem;
use Przeslijmi\Shortquery\Items\LogicOr;
use Przeslijmi\Shortquery\Items\NullVal;
use Przeslijmi\Shortquery\Items\Rule;
use Przeslijmi\Shortquery\Items\TrueVal;
use Przeslijmi\Shortquery\Items\Val;
use Przeslijmi\Shortquery\Items\Vals;

/**
 * Methods for testing Items.
 */
final class ItemsTest extends TestCase
{

    /**
     * Test if creating Comp Item works.
     *
     * @return void
     */
    public function testIfCompItemWorks() : void
    {

        // Create.
        $rule = Rule::factory(new TrueVal(), 'eq', new TrueVal());
        $item = new Comp('eq');
        $item->setRuleParent($rule);
        $item->setSilent(true);

        // Test.
        $this->assertEquals('eq', $item->getMethod());
        $this->assertEquals($rule, $item->getRuleParent());
        $this->assertEquals(true, $item->getSilent());
    }

    /**
     * Test if creating Comp Item throws.
     *
     * @return void
     */
    public function testIfCompItemThrows() : void
    {

        // Prepare.
        $this->expectException(CompCreationFopException::class);

        // Test.
        $item = new Comp('nonexisting_mehtod');
    }

    /**
     * Test if creating TrueVal Item works.
     *
     * @return void
     */
    public function testIfTrueValContentItemWorks() : void
    {

        // Create.
        $rule = Rule::factory(new TrueVal(), 'eq', new TrueVal());
        $func = Func::factory('newFunc', [ 'funcElement' ]);
        $item = new TrueVal();
        $item->setRuleParent($rule);
        $item->setFuncParent($func);
        $item->setAlias('alias');

        // Test.
        $this->assertTrue($item->getValue());
        $this->assertEquals($rule, $item->getRuleParent());
        $this->assertEquals($func, $item->getFuncParent());
        $this->assertTrue($item->isRuleAParent());
        $this->assertEquals('alias', $item->getAlias());
    }

    /**
     * Test if creating FalseVal Item works.
     *
     * @return void
     */
    public function testIfFalseValContentItemWorks() : void
    {

        // Create.
        $rule = Rule::factory(new TrueVal(), 'eq', new TrueVal());
        $func = Func::factory('newFunc', [ 'funcElement' ]);
        $item = new FalseVal();
        $item->setRuleParent($rule);
        $item->setFuncParent($func);
        $item->setAlias('alias');

        // Test.
        $this->assertFalse($item->getValue());
        $this->assertEquals($rule, $item->getRuleParent());
        $this->assertEquals($func, $item->getFuncParent());
        $this->assertTrue($item->isRuleAParent());
        $this->assertEquals('alias', $item->getAlias());
    }

    /**
     * Test if creating Field Item works.
     *
     * @return void
     */
    public function testIfFieldContentItemWorks() : void
    {

        // Create.
        $rule = Rule::factory(new TrueVal(), 'eq', new TrueVal());
        $func = Func::factory('newFunc', [ 'funcElement' ]);
        $item = Field::factory('`table`.`field`');
        $item->setRuleParent($rule);
        $item->setFuncParent($func);
        $item->setAlias('alias');

        // Test.
        $this->assertEquals('table', $item->getTable());
        $this->assertEquals('field', $item->getField());
        $this->assertEquals($rule, $item->getRuleParent());
        $this->assertEquals($func, $item->getFuncParent());
        $this->assertTrue($item->isRuleAParent());
        $this->assertEquals('alias', $item->getAlias());

        // Create next.
        $item = Field::factory('`field`');
        $this->assertEquals('', $item->getTable());
        $this->assertEquals('field', $item->getField());
    }

    /**
     * Test if creating Func Item works.
     *
     * @return void
     */
    public function testIfFuncContentItemWorks() : void
    {

        // Lvd.
        $arrayOfItems = [ [ 1, 2, 3 ], 1, null, '`table`.`field`', 'value' ];

        // Create.
        $rule = Rule::factory(new TrueVal(), 'eq', new TrueVal());
        $func = Func::factory('newFunc', [ 'funcElement' ]);
        $item = Func::factory('func', $arrayOfItems);
        $item->setRuleParent($rule);
        $item->setFuncParent($func);
        $item->setAlias('alias');

        // Test.
        $this->assertEquals('func', $item->getName());
        $this->assertEquals('array', gettype($item->getItems()));
        $this->assertEquals(count($arrayOfItems), $item->countItems());
        $this->assertEquals($rule, $item->getRuleParent());
        $this->assertEquals($func, $item->getFuncParent());
        $this->assertTrue($item->isRuleAParent());
        $this->assertEquals('alias', $item->getAlias());

        // Test items.
        $this->assertTrue(is_a($item->getItem(0), Vals::class));
        $this->assertTrue(is_a($item->getItem(1), IntVal::class));
        $this->assertTrue(is_a($item->getItem(2), NullVal::class));
        $this->assertTrue(is_a($item->getItem(3), Field::class));
        $this->assertTrue(is_a($item->getItem(4), Val::class));

        // Prepare for throwing.
        $this->expectException(FuncItemOtosetException::class);
        $item->getItem(5);
    }

    /**
     * Test if creating wrong Func throws.
     *
     * @return void
     */
    public function testIfFuncContentItemThrows() : void
    {

        // Prepare.
        $this->expectException(FuncWrongComponentsException::class);

        // Test.
        new Func('funcName', [ 'funcVal' ]);
    }

    /**
     * Test if creating IntVal Item works.
     *
     * @return void
     */
    public function testIfIntValContentItemWorks() : void
    {

        // Create.
        $rule = Rule::factory(new TrueVal(), 'eq', new TrueVal());
        $func = Func::factory('newFunc', [ 'funcElement' ]);
        $item = new IntVal(5);
        $item->setRuleParent($rule);
        $item->setFuncParent($func);
        $item->setAlias('alias');

        // Test.
        $this->assertEquals(5, $item->getValue());
        $this->assertEquals($rule, $item->getRuleParent());
        $this->assertEquals($func, $item->getFuncParent());
        $this->assertTrue($item->isRuleAParent());
        $this->assertEquals('alias', $item->getAlias());
    }

    /**
     * Test if ItemsFactory works.
     *
     * @return void
     */
    public function testIfItemsFactoryWorks() : void
    {

        // Comp.
        $item = ItemsFactory::comp('eq');
        $this->assertTrue(is_a($item, Comp::class));

        // Field.
        $item = ItemsFactory::field('field');
        $this->assertTrue(is_a($item, Field::class));

        // Func.
        $item = ItemsFactory::func('func_name', [ 'func_arg' ]);
        $this->assertTrue(is_a($item, Func::class));

        // Val.
        $item = ItemsFactory::val('value');
        $this->assertTrue(is_a($item, Val::class));

        // Vals.
        $item = ItemsFactory::vals([ 'value1', 'value2', 'value3' ]);
        $this->assertTrue(is_a($item, Vals::class));
    }

    /**
     * Test if Logic Item works.
     *
     * @return void
     */
    public function testIfLogicItemWorks() : void
    {

        // Lvd.
        $girls        = new Girls();
        $rule         = Rule::factory(new TrueVal(), 'eq', new TrueVal());
        $preRule      = [ new TrueVal(), 'eq', new TrueVal() ];
        $logicAnd     = new LogicAnd($rule, $rule);
        $logicOr      = new LogicOr($rule, $rule);
        $arrayOfItems = LogicItem::factory($rule, $preRule, $logicAnd, $logicOr);

        // Create.
        $item = $arrayOfItems[0];
        $item->setCollectionParent($girls);

        // Test.
        $this->assertTrue($item->hasRules());
        $this->assertEquals(2, count($item->getRules()));
        $this->assertEquals($girls, $item->getCollectionParent());
    }

    /**
     * Test if creating wrong Logic Item throws.
     *
     * @return void
     */
    public function testIfLogicItemThrows() : void
    {

        // Prepare.
        $this->expectException(LogicWrongComponentsException::class);

        // Test.
        LogicItem::factory(true);
    }

    /**
     * Test if creating NullVal Item works.
     *
     * @return void
     */
    public function testIfNullValContentItemWorks() : void
    {

        // Create.
        $rule = Rule::factory(new TrueVal(), 'eq', new TrueVal());
        $func = Func::factory('newFunc', [ 'funcElement' ]);
        $item = new NullVal();
        $item->setRuleParent($rule);
        $item->setFuncParent($func);
        $item->setAlias('alias');

        // Test.
        $this->assertEquals(null, $item->getValue());
        $this->assertEquals($rule, $item->getRuleParent());
        $this->assertEquals($func, $item->getFuncParent());
        $this->assertTrue($item->isRuleAParent());
        $this->assertEquals('alias', $item->getAlias());
    }

    /**
     * Test if Rule Item works.
     *
     * @return void
     */
    public function testIfRuleItemWorks() : void
    {

        // Lvd.
        $left  = new TrueVal();
        $comp  = new Comp('eq');
        $right = new TrueVal();

        // Create.
        $rule = Rule::factory($left, $comp, $right);

        // Add logic.
        $logic = new LogicAnd($rule, $rule);
        $rule->setLogicItemParent($logic);

        // Test.
        $this->assertEquals($left, $rule->getLeft());
        $this->assertEquals($comp, $rule->getComp());
        $this->assertEquals($right, $rule->getRight());
        $this->assertEquals($logic, $rule->getLogicItemParent());
    }

    /**
     * Test if Rule Item throws.
     *
     * @return void
     */
    public function testIfRuleItemThrows() : void
    {

        // Lvd.
        $true = new TrueVal();

        // Prepare.
        $this->expectException(RuleCreationFopException::class);

        // Create.
        Rule::factory($true, 'error', $true);
    }

    /**
     * Test if wrapping rule works.
     *
     * @return void
     */
    public function testIfWrappignRuleItemWorks() : void
    {

        // Lvd.
        $true = new TrueVal();

        // Create.
        $this->assertTrue(is_a(Rule::factoryWrapped($true), LogicItem::class));
        $this->assertTrue(is_a(Rule::factoryWrapped($true, 'eq'), LogicItem::class));
        $this->assertTrue(is_a(Rule::factoryWrapped($true, 'eq', $true), LogicItem::class));
    }

    /**
     * Test if factory for rule with 1 param works.
     *
     * @return void
     */
    public function testIfRuleFactoryOneParamWorks() : void
    {

        // Create.
        $rule = Rule::factory('`field`');

        // Test.
        $this->assertTrue(is_a($rule->getLeft(), Field::class));
        $this->assertEquals('field', $rule->getLeft()->getField());
    }

    /**
     * Test if factory for rule with 2 params works.
     *
     * @return void
     */
    public function testIfRuleFactoryTwoParamsWorks() : void
    {

        // Create.
        $rule = Rule::factory([ 'concat', [ '`fieldA`', '`fieldB`' ]], [ 'aaa', 'bbb', 'ccc' ]);

        // Test.
        $this->assertTrue(is_a($rule->getLeft(), Func::class));
        $this->assertTrue(is_a($rule->getRight(), Func::class));
        $this->assertEquals('concat', $rule->getLeft()->getName());
        $this->assertEquals('in', $rule->getRight()->getName());
    }

    /**
     * Test if factory for rule with 3 params works.
     *
     * @return void
     */
    public function testIfRuleFactoryThreeParamsWorks() : void
    {

        // Create.
        $rule = Rule::factory('`fieldA`', 'eq', [ 'concat', [ 'aaa', 'bbb', 'ccc' ]]);

        // Test.
        $this->assertTrue(is_a($rule->getLeft(), Field::class));
        $this->assertTrue(is_a($rule->getRight(), Func::class));
        $this->assertEquals('fieldA', $rule->getLeft()->getField());
        $this->assertEquals('concat', $rule->getRight()->getName());

        // Create.
        $rule = Rule::factory('`fieldA`', 'eq', '`fieldB`');

        // Test.
        $this->assertTrue(is_a($rule->getRight(), Field::class));
        $this->assertEquals('fieldB', $rule->getRight()->getField());

        // Create.
        $rule = Rule::factory('`fieldA`', 'eq', 5);

        // Test.
        $this->assertTrue(is_a($rule->getRight(), IntVal::class));
        $this->assertEquals(5, $rule->getRight()->getValue());

        // Create.
        $rule = Rule::factory('`fieldA`', 'eq', null);

        // Test.
        $this->assertTrue(is_a($rule->getRight(), NullVal::class));
        $this->assertEquals(null, $rule->getRight()->getValue());
    }

    /**
     * Test if creating Val Item works.
     *
     * @return void
     */
    public function testIfValContentItemWorks() : void
    {

        // Create.
        $rule = Rule::factory(new TrueVal(), 'eq', new TrueVal());
        $func = Func::factory('newFunc', [ 'funcElement' ]);
        $item = Val::factory('testValue', 'alias');
        $item->setRuleParent($rule);
        $item->setFuncParent($func);

        // Test.
        $this->assertEquals('testValue', $item->getValue());
        $this->assertEquals($rule, $item->getRuleParent());
        $this->assertEquals($func, $item->getFuncParent());
        $this->assertTrue($item->isRuleAParent());
        $this->assertEquals('alias', $item->getAlias());
    }

    /**
     * Test if creating Vals Item works.
     *
     * @return void
     */
    public function testIfValsContentItemWorks() : void
    {

        // Create.
        $rule = Rule::factory(new TrueVal(), 'eq', new TrueVal());
        $func = Func::factory('newFunc', [ 'funcElement' ]);
        $item = new Vals([ 'testValue1', 'testValue2' ]);
        $item->setRuleParent($rule);
        $item->setFuncParent($func);
        $item->setAlias('alias');

        // Test.
        $this->assertEquals([ 'testValue1', 'testValue2' ], $item->getValues());
        $this->assertEquals($rule, $item->getRuleParent());
        $this->assertEquals($func, $item->getFuncParent());
        $this->assertTrue($item->isRuleAParent());
        $this->assertEquals('alias', $item->getAlias());
    }
}
