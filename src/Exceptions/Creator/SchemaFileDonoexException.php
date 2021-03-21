<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Creator;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Creator has to take schema from a file given as param -su (--schemaUri). File is missing.
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class SchemaFileDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Creator has to take schema from a file given as param -su (--schemaUri). File is missing.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'schemaFileUri',
    ];
}
