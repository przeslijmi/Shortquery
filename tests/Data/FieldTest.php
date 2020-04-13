<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Shortquery\Data\Field\DateField;
use Przeslijmi\Shortquery\Data\Field\DecimalField;
use Przeslijmi\Shortquery\Data\Field\EnumField;
use Przeslijmi\Shortquery\Data\Field\IntField;
use Przeslijmi\Shortquery\Data\Field\JsonField;
use Przeslijmi\Shortquery\Data\Field\SetField;
use Przeslijmi\Shortquery\Data\Field\VarCharField;
use Przeslijmi\Shortquery\Data\Model;

/**
 * Methods for testing Field.
 */
final class FieldTest extends TestCase
{

    /**
     * Data provider with all Field classes.
     *
     * @return array
     */
    public function fieldsDataProvider() : array
    {

        return [
            [ 'DateField',    'DateField',    'date',    'string ', 'string',   'string',          'string'   ],
            [ 'DecimalField', 'DecimalField', 'decimal', 'float ',  'float',    'float',           'float'    ],
            [ 'EnumField',    'EnumField',    'enum',    'string ', 'string',   'string',          'string'   ],
            [ 'IntField',     'IntField',     'int',     'int ',    'int',      'integer',         'integer'  ],
            [ 'JsonField',    'JsonField',    'json',    '',        'stdClass', 'string|stdClass', 'stdClass' ],
            [ 'SetField',     'SetField',     'set',     'string ', 'string',   'string',          'string'   ],
            [ 'VarCharField', 'VarCharField', 'varchar', 'string ', 'string',   'string',          'string'   ],
        ];
    }

    /**
     * Test if creating model properly works.
     *
     * @param string $fieldClass        Name of field class.
     * @param string $type              Name of type (?) of field class.
     * @param string $engineType        Variable type in engine.
     * @param string $phpTypeInput      Variable type in php on input.
     * @param string $phpTypeOutput     Variable type in php on ouput.
     * @param string $phpDocsTypeInput  Variable type in php docs on input.
     * @param string $phpDocsTypeOutput Variable type in php docs on ouput.
     *
     * @return void
     *
     * @dataProvider fieldsDataProvider
     */
    public function testIfCreatingFieldWorks(
        string $fieldClass,
        string $type,
        string $engineType,
        string $phpTypeInput,
        string $phpTypeOutput,
        string $phpDocsTypeInput,
        string $phpDocsTypeOutput
    ) : void {

        // Lvd.
        $fieldClass = 'Przeslijmi\\Shortquery\\Data\\Field\\' . $fieldClass;

        // Create Model.
        $model = new Model();
        $model->setName('test');
        $model->setInstanceClassName('Test');

        // Create Field.
        $field = new $fieldClass('test_name');
        $field->setModel($model);
        $field->setPrimaryKey(true);
        $field->setPk(true);

        // Test.
        $this->assertEquals($fieldClass, get_class($field));
        $this->assertEquals('test_name', $field->getName());
        $this->assertEquals('testName', $field->getName('camelCase'));
        $this->assertEquals('TestName', $field->getName('pascalCase'));
        $this->assertEquals($model, $field->getModel());
        $this->assertTrue($field->isPrimaryKey());
        $this->assertTrue($field->isNotNull());
        $this->assertEquals($type, $field->getType());
        $this->assertEquals($engineType, $field->getEngineType());
        $this->assertEquals($phpTypeInput, $field->getPhpTypeInput());
        $this->assertEquals($phpTypeOutput, $field->getPhpTypeOutput());
        $this->assertEquals($phpDocsTypeInput, $field->getPhpDocsTypeInput());
        $this->assertEquals($phpDocsTypeOutput, $field->getPhpDocsTypeOutput());
        $this->assertEquals('getTestName', $field->getGetterName());
        $this->assertEquals('setTestName', $field->getSetterName());
        $this->assertEquals('string', gettype($field->toPhp()));
        $this->assertEquals('string', gettype($field->getterToPhp()));
        $this->assertEquals('string', gettype($field->compareToPhp()));
        $this->assertEquals('string', gettype($field->extraMethodsToPhp($model)));
        $this->assertEquals('string', gettype($field->getProperValueHint()));
    }

    /**
     * Test if all echoing methods that are used to crete PHP code.
     *
     * @return void
     *
     * @dataProvider fieldsDataProvider
     */
    public function testEchoingMethods() : void
    {

        // Create Field.
        $field = new DateField('test_name');
        $testS = 'test';
        $testA = [ 'a', 'b' ];

        // Tests.
        $this->assertEquals('        ', $field->ind(2));
        $this->assertEquals('        test line' . "\n\n", $field->ln(2, 'test line', 2));
        $this->assertEquals(var_export($testS, true), $field->ex($testS));
        $this->assertEquals('\'a\', \'b\'', $field->imp($testA));
        $this->assertEquals('\'a\',\'b\'', $field->csv($testA));
        $this->assertEquals('testName', $field->cc());
        $this->assertEquals('$this->testName', $field->cc(true));
    }
}
