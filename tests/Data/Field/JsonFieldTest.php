<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Data\Field\JsonField;
use Przeslijmi\Shortquery\Exceptions\Items\FieldValueInproperException;
use Przeslijmi\Shortquery\Exceptions\Items\FieldDefinitionWrosynException;
use stdClass;

/**
 * Methods for testing JsonField.
 */
final class JsonFieldTest extends TestCase
{

    /**
     * Test methods specific for JsonField.
     *
     * @return void
     */
    public function testIfWorks() : void
    {

        // Lvd.
        $testStdClass       = new stdClass();
        $testStdClass->test = true;

        // Create Field.
        $field = new JsonField('test_name');

        // Test.
        $this->assertTrue($field->isValueValid(null));
        $this->assertTrue($field->isValueValid(new stdClass()));
        $this->assertEquals(null, $field->setProperType(null));
        $this->assertEquals('{"test":true}', json_encode($field->setProperType($testStdClass)));
        $this->assertEquals('{"scalar":"test"}', json_encode($field->setProperType('test')));
        $this->assertEquals('{"stuff":"things"}', json_encode($field->setProperType('{"stuff":"things"}')));
    }
}
