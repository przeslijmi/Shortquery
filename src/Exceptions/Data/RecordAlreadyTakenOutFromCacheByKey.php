<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Data;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Key has been already taken from this cache.
 *
 * If you want to get from cache more than one - use `get()`, not `getOnce()` method.
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class RecordAlreadyTakenOutFromCacheByKey extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Key has been already taken from this cache. If you want to get from cache more than one - use `get()`, not `getOnce()` method.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'keyValue',
        'model',
        'modelName',
    ];
}
