<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Creator;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Configuration file has to be JSON format, readable for PHP.
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class SchemaFileCorruptedException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Configuration file has to be JSON format, readable for PHP. File is locked for read or has corrupted contents.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'schemaFileUri',
    ];
}
