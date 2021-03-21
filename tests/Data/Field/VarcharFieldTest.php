<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Data\Field\VarcharField;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Exceptions\Items\FieldDefinitionWrosynException;
use Przeslijmi\Shortquery\Exceptions\Items\FieldValueInproperException;

/**
 * Methods for testing VarcharField.
 */
final class VarcharFieldTest extends TestCase
{

    /**
     * Test methods specific for VarcharField.
     *
     * @return void
     */
    public function testIfWorks() : void
    {

        // Create Field.
        $field = new VarcharField('test_name');
        $field->setMaxLength(5);

        // Test.
        $this->assertEquals(5, $field->getMaxLength());
        $this->assertTrue($field->isValueValid(null));
        $this->assertTrue($field->isValueValid('aa'));
        $this->assertTrue($field->isValueValid('  '));
        $this->assertFalse($field->isValueValid('aaaaaa', false));
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
        $field = new VarcharField('test_name');
        $field->setModel(( new Model() )->setName('test'));
        $field->setMaxLength(-5);
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
        $field = new VarcharField('test_name');
        $field->setModel(( new Model() )->setName('test'));
        $field->setMaxLength(9000);
    }

    /**
     * Test if setting wrong value will throw.
     *
     * @return void
     */
    public function testIfWrongValueThrows() : void
    {

        $this->expectException(FieldValueInproperException::class);

        // Create Field.
        $field = new VarcharField('test_name');
        $field->setModel(( new Model() )->setName('test'));
        $field->setMaxLength(3);

        // Test.
        $this->assertFalse($field->isValueValid('1234'));
    }
}
