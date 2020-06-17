<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\CacheByKey;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Midbase `hash.json` file does not exists.
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class CacheElementMissingException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Cache element in this `CacheByKey` object does not exists.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [ 'modelClassName', 'fieldInModelName', 'missingKeyValue' ];
}
