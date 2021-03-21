<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Data\Field\EnumField;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Exceptions\Items\FieldDictDonoexException;
use Przeslijmi\Shortquery\Exceptions\Items\FieldDictValueDonoexException;
use Przeslijmi\Shortquery\Exceptions\Items\FieldValueInproperException;

/**
 * Methods for testing EnumField.
 */
final class EnumFieldTest extends TestCase
{

    /**
     * Test methods specific for EnumField.
     *
     * @return void
     */
    public function testIfWorks() : void
    {

        // Lvd.
        $dicts = [
            'main' => [ 'one', 'two', 'three' ],
            'pl:pl' => [ 'jeden', 'dwa', 'trzy' ],
        ];

        // Create Field.
        $field = new EnumField('test_name');
        $field->setValues(...$dicts['main']);
        $field->setDict('pl:pl', ...$dicts['pl:pl']);

        // Test.
        $this->assertEquals($dicts['main'], $field->getValues());
        $this->assertEquals($dicts['main'], $field->getMainDict());
        $this->assertEquals($dicts['pl:pl'], $field->getDict('pl:pl'));
        $this->assertEquals($dicts, $field->getDicts());
        $this->assertEquals($dicts['main'][0], $field->getDictValue('one'));
        $this->assertEquals($dicts['main'][0], $field->getDictValue('one', 'main'));
        $this->assertEquals($dicts['pl:pl'][0], $field->getDictValue('one', 'pl:pl'));
        $this->assertTrue($field->isValueValid(null));
        $this->assertTrue($field->isValueValid('one'));
        $this->assertFalse($field->isValueValid('jeden', false));
        $this->assertFalse($field->isValueValid('1', false));
    }

    /**
     * Test if getting nonexisting dict throws.
     *
     * @return void
     */
    public function testIfGettingNonexitingDictThrows() : void
    {

        $this->expectException(FieldDictDonoexException::class);

        // Create Field.
        $field = new EnumField('test_name');
        $field->setModel(( new Model() )->setName('test'));
        $field->setValues('one', 'two', 'three');
        $field->getDict('nonexisting_dict');
    }

    /**
     * Test if getting nonexisting dict value throws.
     *
     * @return void
     */
    public function testIfGettingNonexitingDictValueThrows() : void
    {

        $this->expectException(FieldDictValueDonoexException::class);

        // Create Field.
        $field = new EnumField('test_name');
        $field->setModel(( new Model() )->setName('test'));
        $field->setValues('one', 'two', 'three');
        $field->getDictValue('nonexistingKey', 'main');
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
        $field = new EnumField('test_name');
        $field->setModel(( new Model() )->setName('test'));
        $field->setValues('one', 'two', 'three');

        // Test.
        $this->assertFalse($field->isValueValid('four'));
    }
}
