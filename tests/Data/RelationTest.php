<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Data\Relation\HasManyRelation;
use Przeslijmi\Shortquery\Exceptions\Model\RelationFieldFromDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\RelationFieldFromIsCorrupted;
use Przeslijmi\Shortquery\Exceptions\Model\RelationFieldToDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\RelationFieldToIsCorrupted;
use Przeslijmi\Shortquery\Exceptions\Model\RelationModelFromDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\RelationModelFromIsCorrupted;
use Przeslijmi\Shortquery\Exceptions\Model\RelationModelToDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\RelationModelToIsCorrupted;
use Przeslijmi\Shortquery\Exceptions\Model\RelationNameWrosynException;
use Przeslijmi\Shortquery\Exceptions\Model\RelationFailedToCreateRule;
use Przeslijmi\Shortquery\ForTests\Models\Core\CarModel;
use Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel;

/**
 * Methods for testing Relation.
 */
final class RelationTest extends TestCase
{

    /**
     * Test if reading existing relation works.
     *
     * @return void
     */
    public function testIfReadingRelationWorks() : void
    {

        // Get created Relation.
        $model    = new GirlModel();
        $relation = $model->getRelationByName('cars');

        // Test.
        $this->assertEquals('hasMany', $relation->getType());
        $this->assertEquals('cars', $relation->getName());
        $this->assertInstanceOf(GirlModel::class, $relation->getModelFrom());
        $this->assertEquals(GirlModel::class, $relation->getModelFromAsName());
        $this->assertInstanceOf(CarModel::class, $relation->getModelTo());
        $this->assertInstanceOf(GirlModel::class, $relation->getModelOtherThan('cars'));
        $this->assertInstanceOf(CarModel::class, $relation->getModelOtherThan('girls'));
        $this->assertEquals(CarModel::class, $relation->getModelToAsName());
        $this->assertInstanceOf(Field::class, $relation->getFieldFrom());
        $this->assertEquals('pk', $relation->getFieldFrom()->getName());
        $this->assertEquals('pk', $relation->getFieldFromAsName());
        $this->assertInstanceOf(Field::class, $relation->getFieldTo());
        $this->assertEquals('owner_girl', $relation->getFieldTo()->getName());
        $this->assertEquals('owner_girl', $relation->getFieldToAsName());
        $this->assertEquals('expandCars', $relation->getExpanderName());
        $this->assertEquals('addCars', $relation->getAdderName());
        $this->assertEquals('getCars', $relation->getGetterName());
        $this->assertEquals('pk', $relation->getFieldFromModelOtherThan('cars')->getName());
        $this->assertEquals('owner_girl', $relation->getFieldFromModelOtherThan('girls')->getName());
    }

    /**
     * Test if reading existing relation with logics works.
     *
     * @return void
     *
     * @dependsa testModelCreator
     */
    public function testIfReadingRelationWithLogicsWorks() : void
    {

        // Get created Relation.
        $model    = new GirlModel();
        $relation = $model->getRelationByName('fastCars');

        // Test.
        $this->assertEquals(1, count($relation->getLogics()));
        $this->assertTrue($relation->hasLogics());
    }

    /**
     * Test if creating relation with wrong name throws.
     *
     * @return void
     */
    public function testIfCreatingRelationWithWrongNameThrows() : void
    {

        $this->expectException(RelationNameWrosynException::class);

        // Create Model without name and ask for it.
        $relation = new HasManyRelation(' 11 .');
    }

    /**
     * Test if reading nonexisting ModelFrom throws 1.
     *
     * @return void
     */
    public function testIfReadingNonexistingModelFromThrows1() : void
    {

        $this->expectException(RelationModelFromDonoexException::class);

        // Create Model without name and ask for it.
        $relation = new HasManyRelation('test');
        $relation->getModelFrom();
    }

    /**
     * Test if reading nonexisting ModelFrom throws 2.
     *
     * @return void
     */
    public function testIfReadingNonexistingModelFromThrows2() : void
    {

        $this->expectException(RelationModelFromDonoexException::class);

        // Create Model without name and ask for it.
        $relation = new HasManyRelation('test');
        $relation->getModelFromAsName();
    }

    /**
     * Test if instantiating model from from nonexisting class throws.
     *
     * @return void
     */
    public function testIfInstantiatingNonexistingModelFromThrows() : void
    {

        $this->expectException(RelationModelFromIsCorrupted::class);

        // Create Model without name and ask for it.
        $relation = new HasManyRelation('test');
        $relation->setModelFrom('nonexistingModel');
        $relation->getModelFrom();
    }

    /**
     * Test if reading nonexisting ModelTo throws 1.
     *
     * @return void
     */
    public function testIfReadingNonexistingModelToThrows1() : void
    {

        $this->expectException(RelationModelToDonoexException::class);

        // Create Model without name and ask for it.
        $relation = new HasManyRelation('test');
        $relation->getModelTo();
    }

    /**
     * Test if reading nonexisting ModelTo throws 2.
     *
     * @return void
     */
    public function testIfReadingNonexistingModelToThrows2() : void
    {

        $this->expectException(RelationModelToDonoexException::class);

        // Create Model without name and ask for it.
        $relation = new HasManyRelation('test');
        $relation->getModelToAsName();
    }

    /**
     * Test if instantiating model from from nonexisting class throws.
     *
     * @return void
     */
    public function testIfInstantiatingNonexistingModelToThrows() : void
    {

        $this->expectException(RelationModelToIsCorrupted::class);

        // Create Model without name and ask for it.
        $relation = new HasManyRelation('test');
        $relation->setModelTo('nonexistingModel');
        $relation->getModelTo();
    }

    /**
     * Test if reading nonexisting FieldFrom throws 1.
     *
     * @return void
     */
    public function testIfReadingNonexistingFieldFromThrows1() : void
    {

        $this->expectException(RelationFieldFromDonoexException::class);

        // Create Model without name and ask for it.
        $relation = new HasManyRelation('test');
        $relation->getFieldFrom();
    }

    /**
     * Test if reading nonexisting FieldFrom throws 2.
     *
     * @return void
     */
    public function testIfReadingNonexistingFieldFromThrows2() : void
    {

        $this->expectException(RelationFieldFromDonoexException::class);

        // Create Model without name and ask for it.
        $relation = new HasManyRelation('test');
        $relation->getFieldFromAsName();
    }

    /**
     * Test if instantiating model from from nonexisting class throws.
     *
     * @return void
     */
    public function testIfInstantiatingNonexistingFieldFromThrows() : void
    {

        $this->expectException(RelationFieldFromIsCorrupted::class);

        // Create Model without name and ask for it.
        $relation = new HasManyRelation('test');
        $relation->setFieldFrom('nonexistingModel');
        $relation->getFieldFrom();
    }

    /**
     * Test if reading nonexisting FieldTo throws 1.
     *
     * @return void
     */
    public function testIfReadingNonexistingFieldToThrows1() : void
    {

        $this->expectException(RelationFieldToDonoexException::class);

        // Create Model without name and ask for it.
        $relation = new HasManyRelation('test');
        $relation->getFieldTo();
    }

    /**
     * Test if reading nonexisting FieldTo throws 2.
     *
     * @return void
     */
    public function testIfReadingNonexistingFieldToThrows2() : void
    {

        $this->expectException(RelationFieldToDonoexException::class);

        // Create Model without name and ask for it.
        $relation = new HasManyRelation('test');
        $relation->getFieldToAsName();
    }

    /**
     * Test if instantiating model from from nonexisting class throws.
     *
     * @return void
     */
    public function testIfInstantiatingNonexistingFieldToThrows() : void
    {

        $this->expectException(RelationFieldToIsCorrupted::class);

        // Create Model without name and ask for it.
        $relation = new HasManyRelation('test');
        $relation->setFieldTo('nonexistingModel');
        $relation->getFieldTo();
    }

    /**
     * Test if creating relation with wrong Rule throws.
     *
     * @return void
     */
    public function testIfCallingToCreateInproperRelationRuleThrows() : void
    {

        $this->expectException(RelationFailedToCreateRule::class);

        // Create Model without name and ask for it.
        $relation = new HasManyRelation('test');
        $relation->addRule([]);
    }
}
