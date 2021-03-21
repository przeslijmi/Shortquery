<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Data\Field\DateField;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Exceptions\Items\FieldValueInproperException;

/**
 * Methods for testing DateField.
 */
final class DataFieldTest extends TestCase
{

    /**
     * Test methods specific for DateField.
     *
     * @return void
     */
    public function testIfWorks() : void
    {

        // Create Field.
        $field = new DateField('test_name');

        // Test.
        $this->assertTrue($field->isValueValid(null));
        $this->assertTrue($field->isValueValid('2019-01-01'));
        $this->assertFalse($field->isValueValid('2019-01-32', false));
        $this->assertEquals(43466, $field->formatToExcel('2019-01-01'));
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
        $field = new DateField('test_name');
        $field->setModel(( new Model() )->setName('test'));

        // Test.
        $this->assertFalse($field->isValueValid('2019-01-32'));
    }
}
