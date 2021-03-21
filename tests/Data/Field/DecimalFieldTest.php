<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Data\Field\DecimalField;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Exceptions\Items\FieldDefinitionWrosynException;
use Przeslijmi\Shortquery\Exceptions\Items\FieldValueInproperException;

/**
 * Methods for testing DecimalField.
 */
final class DecimalFieldTest extends TestCase
{

    /**
     * Test methods specific for DecimalField.
     *
     * @return void
     */
    public function testIfWorks() : void
    {

        // Create Field.
        $field = new DecimalField('test_name');
        $field->setSize(5, 3);

        // Test.
        $this->assertEquals(5, $field->getMaxLength());
        $this->assertEquals(3, $field->getFractionDigits());
        $this->assertTrue($field->isValueValid(null));
        $this->assertTrue($field->isValueValid(12.331));
        $this->assertFalse($field->isValueValid(12.3311, false));
        $this->assertFalse($field->isValueValid(112.331, false));
        $this->assertFalse($field->isValueValid(1.3311, false));
    }

    /**
     * Test if setting wrong max length 1 will throw.
     *
     * @return void
     */
    public function testIfWrongMaxLengthDefinition1Throws() : void
    {

        $this->expectException(FieldDefinitionWrosynException::class);

        // Create Field.
        $field = new DecimalField('test_name');
        $field->setModel(( new Model() )->setName('test'));
        $field->setSize(-5, 3);
    }

    /**
     * Test if setting wrong max length 2 will throw.
     *
     * @return void
     */
    public function testIfWrongMaxLengthDefinition2Throws() : void
    {

        $this->expectException(FieldDefinitionWrosynException::class);

        // Create Field.
        $field = new DecimalField('test_name');
        $field->setModel(( new Model() )->setName('test'));
        $field->setSize(22, 3);
    }

    /**
     * Test if setting wrong fraction digits 1 will throw.
     *
     * @return void
     */
    public function testIfWrongFractionDigitsDefinition1Throws() : void
    {

        $this->expectException(FieldDefinitionWrosynException::class);

        // Create Field.
        $field = new DecimalField('test_name');
        $field->setModel(( new Model() )->setName('test'));
        $field->setSize(5, -3);
    }

    /**
     * Test if setting wrong fraction digits 2 will throw.
     *
     * @return void
     */
    public function testIfWrongFractionDigitsDefinition2Throws() : void
    {

        $this->expectException(FieldDefinitionWrosynException::class);

        // Create Field.
        $field = new DecimalField('test_name');
        $field->setModel(( new Model() )->setName('test'));
        $field->setSize(5, 6);
    }

    /**
     * Test if setting wrong value will throw 1.
     *
     * @return void
     */
    public function testIfWrongValueThrows1() : void
    {

        $this->expectException(FieldValueInproperException::class);

        // Create Field.
        $field = new DecimalField('test_name');
        $field->setModel(( new Model() )->setName('test'));
        $field->setSize(5, 3);

        // Test.
        $this->assertFalse($field->isValueValid(12.3311));
    }

    /**
     * Test if setting wrong value will throw 2.
     *
     * @return void
     */
    public function testIfWrongValueThrows2() : void
    {

        $this->expectException(FieldValueInproperException::class);

        // Create Field.
        $field = new DecimalField('test_name');
        $field->setModel(( new Model() )->setName('test'));
        $field->setSize(5, 3);

        // Test.
        $this->assertFalse($field->isValueValid(112.331));
    }
}
