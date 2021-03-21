<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Data\Field\IntField;
use Przeslijmi\Shortquery\Data\Field\VarCharField;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Data\Relation\HasManyRelation;
use Przeslijmi\Shortquery\Data\Relation\HasOneRelation;
use Przeslijmi\Shortquery\Exceptions\Data\CollectionUnknownNameGetterException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelCollectionClassNameDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelCollectionClassNameWrosynException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelDatabaseDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelDatabaseNameOtosetException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelEngineDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelFieldDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelFieldNameAlrexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelInstanceClassNameDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelInstanceClassNameWrosynException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelNameDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelNamespaceDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelNamespaceWrosynException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelNameWrosynException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelPrimaryKeyFieldAlrexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelPrimaryKeyFieldDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelQueryCreationFailedException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelRelationDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelRelationNameAlrexException;

/**
 * Methods for testing Model.
 */
final class ModelTest extends TestCase
{

    /**
     * Test if creating model properly works.
     *
     * @return void
     */
    public function testIfCreatingModelWorks() : void
    {

        // Lvd.
        $createClass = PRZESLIJMI_SHORTQUERY_ENGINES['mySql']['createQuery'];
        $readClass   = PRZESLIJMI_SHORTQUERY_ENGINES['mySql']['readQuery'];
        $updateClass = PRZESLIJMI_SHORTQUERY_ENGINES['mySql']['updateQuery'];
        $deleteClass = PRZESLIJMI_SHORTQUERY_ENGINES['mySql']['deleteQuery'];

        // Preapare fields.
        $fields = [
            'pk'        => ( new IntField('pk') )->setPrimaryKey(true),
            'full_name' => ( new VarCharField('full_name') ),
        ];

        // Preapare relations.
        $relations = [
            'deeper' => ( new HasOneRelation('deeper') )
                ->setModelFrom('Clean\\Nice\\Namespace\\Core\\TestModel')
                ->setFieldFrom('pk')
                ->setModelTo('Clean\\Nice\\Namespace\\Core\\DeepModel')
                ->setFieldTo('pk')
        ];

        // Create Model.
        $model = new Model();
        $model->setName('test');
        $model->setDatabases('test');
        $model->setNamespace('Clean\\Nice\\Namespace');
        $model->setInstanceClassName('Test');
        $model->setCollectionClassName('Tests');
        $model->addField($fields['pk']);
        $model->addField($fields['full_name']);
        $model->addRelation($relations['deeper']);

        $this->assertEquals('test', $model->getName());
        $this->assertEquals([ 'test' ], $model->getDatabases());
        $this->assertEquals('\'test\'', $model->getDatabasesAsString());
        $this->assertEquals('test', $model->getDatabase());
        $this->assertEquals('test', $model->getDatabase('test'));
        $this->assertEquals('mySql', $model->getEngine());
        $this->assertEquals('mySql', $model->getEngine('test'));
        $this->assertEquals('mySql', $model->getEngine('test'));
        $this->assertInstanceOf($createClass, $model->newInsert());
        $this->assertInstanceOf($updateClass, $model->newUpdate());
        $this->assertInstanceOf($readClass, $model->newSelect());
        $this->assertInstanceOf($deleteClass, $model->newDelete());
        $this->assertEquals('Clean\\Nice\\Namespace', $model->getNamespace());
        $this->assertEquals('Clean', $model->getNamespace(0, 1));
        $this->assertEquals('Nice\\Namespace', $model->getNamespace(1, 2));
        $this->assertEquals('Test', $model->getInstanceClassName());
        $this->assertEquals('Tests', $model->getCollectionClassName());
        $this->assertEquals('Clean\\Nice\\Namespace', $model->getClass('namespace'));
        $this->assertEquals('Clean\\Nice\\Namespace\\Core', $model->getClass('namespaceCore'));
        $this->assertEquals('Clean\\Nice\\Namespace\\Core\\TestModel', $model->getClass('modelClass'));
        $this->assertEquals('TestModel', $model->getClass('modelClassName'));
        $this->assertEquals('Clean\\Nice\\Namespace\\Test', $model->getClass('instanceClass'));
        $this->assertEquals('Test', $model->getClass('instanceClassName'));
        $this->assertEquals('TestCore', $model->getClass('instanceCoreClassName'));
        $this->assertEquals('Clean\\Nice\\Namespace\\Tests', $model->getClass('collectionClass'));
        $this->assertEquals('Tests', $model->getClass('collectionClassName'));
        $this->assertEquals('TestsCore', $model->getClass('collectionCoreClassName'));
        $this->assertEquals('Namespace', $model->getClass('parentClassName'));
        $this->assertEquals($fields, $model->getFields());
        $this->assertEquals($fields['pk'], $model->getFieldByName('pk'));
        $this->assertEquals($fields['pk'], $model->getFieldByNameIfExists('pk'));
        $this->assertEquals(null, $model->getFieldByNameIfExists('pk_nonexisting'));
        $this->assertEquals($fields['pk'], $model->getPrimaryKeyField());
        $this->assertEquals($fields['pk'], $model->getPkField());
        $this->assertEquals(array_keys($fields), $model->getFieldsNames());
        $this->assertEquals([ 'getPk', 'getFullName' ], $model->getFieldsGettersNames());
        $this->assertEquals([ 'setPk', 'setFullName' ], $model->getFieldsSettersNames());
        $this->assertEquals($relations, $model->getRelations());
        $this->assertEquals($relations['deeper'], $model->getRelationByName('deeper'));
        $this->assertEquals([ 'deeper' ], $model->getRelationsNames());
    }

    /**
     * Test if creating model with wrong name throws.
     *
     * @return void
     */
    public function testIfCreatingModelWithWrongNameThrows() : void
    {

        $this->expectException(ModelNameWrosynException::class);

        // Create Model with wrong name.
        $model = new Model();
        $model->setName(' ');
    }

    /**
     * Test if reading nonexisting name of model throws.
     *
     * @return void
     */
    public function testIfReadingNonexistingModelNameThrows() : void
    {

        $this->expectException(ModelNameDonoexException::class);

        // Create Model without name and ask for it.
        $model = new Model();
        $model->getName();
    }

    /**
     * Test if creating model with wrong database throws.
     *
     * @return void
     */
    public function testIfCreatingModelWithWrongDatabaseThrows() : void
    {

        $this->expectException(ModelDatabaseNameOtosetException::class);

        // Create Model with wrong name.
        $model = new Model();
        $model->setName('test');
        $model->setDatabases('nonexistingDatabase');
    }

    /**
     * Test if getting database from model without database throws.
     *
     * @return void
     */
    public function testIfGettingDatabaseFromModelWithoutDatabaseThrows() : void
    {

        $this->expectException(ModelDatabaseDonoexException::class);

        // Create Model with wrong name.
        $model = new Model();
        $model->setName('test');
        $model->getDatabase();
    }

    /**
     * Test if getting engine from model without database throws.
     *
     * @return void
     */
    public function testIfGettingEngineFromModelWithoutDatabaseThrows() : void
    {

        $this->expectException(ModelEngineDonoexException::class);

        // Create Model with wrong name.
        $model = new Model();
        $model->setName('test');
        $model->getEngine();
    }

    /**
     * Test if getting SELECT QUERY from model without database throws.
     *
     * @return void
     */
    public function testIfGettingSelectQueryFromModelWithoutDatabaseThrows() : void
    {

        $this->expectException(ModelQueryCreationFailedException::class);

        // Create Model with wrong name.
        $model = new Model();
        $model->setName('test');
        $model->newSelect();
    }

    /**
     * Test if getting UPDATE QUERY from model without database throws.
     *
     * @return void
     */
    public function testIfGettingUpdateQueryFromModelWithoutDatabaseThrows() : void
    {

        $this->expectException(ModelQueryCreationFailedException::class);

        // Create Model with wrong name.
        $model = new Model();
        $model->setName('test');
        $model->newUpdate();
    }

    /**
     * Test if getting INSERT QUERY from model without database throws.
     *
     * @return void
     */
    public function testIfGettingInsertQueryFromModelWithoutDatabaseThrows() : void
    {

        $this->expectException(ModelQueryCreationFailedException::class);

        // Create Model with wrong name.
        $model = new Model();
        $model->setName('test');
        $model->newInsert();
    }

    /**
     * Test if getting DELETE QUERY from model without database throws.
     *
     * @return void
     */
    public function testIfGettingDeleteQueryFromModelWithoutDatabaseThrows() : void
    {

        $this->expectException(ModelQueryCreationFailedException::class);

        // Create Model with wrong name.
        $model = new Model();
        $model->setName('test');
        $model->newDelete();
    }

    /**
     * Test if creating model with wrong namespace throws.
     *
     * @return void
     */
    public function testIfCreatingModelWithWrongNamespaceThrows() : void
    {

        $this->expectException(ModelNamespaceWrosynException::class);

        // Create Model with wrong name.
        $model = new Model();
        $model->setNamespace(' ');
    }

    /**
     * Test if reading nonexisting namespace of model throws.
     *
     * @return void
     */
    public function testIfReadingNonexistingModelNamespaceThrows() : void
    {

        $this->expectException(ModelNamespaceDonoexException::class);

        // Create Model without namespace and ask for it.
        $model = new Model();
        $model->setName('test');
        $model->getNamespace();
    }

    /**
     * Test if creating model with wrong instance class name throws.
     *
     * @return void
     */
    public function testIfCreatingModelWithWrongInstanceClassNameThrows() : void
    {

        $this->expectException(ModelInstanceClassNameWrosynException::class);

        // Create Model with wrong name.
        $model = new Model();
        $model->setInstanceClassName(' ');
    }

    /**
     * Test if reading nonexisting instance class name of model throws.
     *
     * @return void
     */
    public function testIfReadingNonexistingModelInstanceClassNameThrows() : void
    {

        $this->expectException(ModelInstanceClassNameDonoexException::class);

        // Create Model without instance class name and ask for it.
        $model = new Model();
        $model->setName('test');
        $model->getInstanceClassName();
    }

    /**
     * Test if creating model with wrong collection class name throws.
     *
     * @return void
     */
    public function testIfCreatingModelWithWrongCollectionClassNameThrows() : void
    {

        $this->expectException(ModelCollectionClassNameWrosynException::class);

        // Create Model with wrong name.
        $model = new Model();
        $model->setCollectionClassName(' ');
    }

    /**
     * Test if reading nonexisting collection class name of model throws.
     *
     * @return void
     */
    public function testIfReadingNonexistingModelCollectionClassNameThrows() : void
    {

        $this->expectException(ModelCollectionClassNameDonoexException::class);

        // Create Model without collection class name and ask for it.
        $model = new Model();
        $model->setName('test');
        $model->getCollectionClassName();
    }

    /**
     * Test if asking for wrong class name throws.
     *
     * @return void
     */
    public function testIfAskingForWrongClassNameThrows() : void
    {

        $this->expectException(CollectionUnknownNameGetterException::class);

        // Create Model without collection class name and ask for it.
        $model = new Model();
        $model->getClass('nonexistingClass');
    }

    /**
     * Test if adding two fields with identical name throws.
     *
     * @return void
     */
    public function testIfAddingTwoFieldsOfOneNameThrows() : void
    {

        $this->expectException(ModelFieldNameAlrexException::class);

        // Create Model without collection class name and ask for it.
        $model = new Model();
        $model->setName('test');
        $model->addField(( new IntField('pk') ));
        $model->addField(( new IntField('pk') ));
    }

    /**
     * Test if getting nonexisting field throws.
     *
     * @return void
     */
    public function testIfGettingNonexistingFieldThrows() : void
    {

        $this->expectException(ModelFieldDonoexException::class);

        // Create Model without collection class name and ask for it.
        $model = new Model();
        $model->setName('test');
        $model->getFieldByName('what');
    }

    /**
     * Test if getting nonexisting primary key field throws.
     *
     * @return void
     */
    public function testIfGettingNonexistingPrimaryKeyFieldThrows() : void
    {

        $this->expectException(ModelPrimaryKeyFieldDonoexException::class);

        // Create Model without collection class name and ask for it.
        $model = new Model();
        $model->setName('test');
        $model->getPrimaryKeyField();
    }

    /**
     * Test if getting more than one primary key field throws.
     *
     * @return void
     */
    public function testIfGettingMoreThanOnePrimaryKeyFieldThrows() : void
    {

        $this->expectException(ModelPrimaryKeyFieldAlrexException::class);

        // Create Model without collection class name and ask for it.
        $model = new Model();
        $model->setName('test');
        $model->addField(( new IntField('pk1') )->setPrimaryKey(true));
        $model->addField(( new IntField('pk2') )->setPrimaryKey(true));
        $model->getPrimaryKeyField();
    }

    /**
     * Test if adding two relations with identical name throws.
     *
     * @return void
     */
    public function testIfAddingTwoRelationsOfOneNameThrows() : void
    {

        $this->expectException(ModelRelationNameAlrexException::class);

        // Create Model without collection class name and ask for it.
        $model = new Model();
        $model->setName('test');
        $model->setNamespace('This\\Is\\Just\\Test');
        $model->setInstanceClassName('Test');
        $model->addRelation(( new HasOneRelation('relation') ));
        $model->addRelation(( new HasOneRelation('relation') ));
    }

    /**
     * Test if getting nonexisting relation throws.
     *
     * @return void
     */
    public function testIfGettingNonexistingRelationThrows() : void
    {

        $this->expectException(ModelRelationDonoexException::class);

        // Create Model without collection class name and ask for it.
        $model = new Model();
        $model->setName('test');
        $model->getRelationByName('what');
    }
}
