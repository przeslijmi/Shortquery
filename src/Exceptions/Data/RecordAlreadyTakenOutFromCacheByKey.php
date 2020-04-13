<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Data;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\CacheByKey;

/**
 * Record was already taken out from CacheByKey instance.
 */
class RecordAlreadyTakenOutFromCacheByKey extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param mixed      $keyValue   Key value that is missing.
     * @param CacheByKey $cacheByKey Whole CacheByKey instance.
     */
    public function __construct($keyValue, CacheByKey $cacheByKey)
    {

        // Lvd.
        $hint  = 'Key has been already taken from this cache. If you want to get from cache ';
        $hint .= 'more than one - use `get()`, not `getOnce()` method.';

        // Define.
        $this->addInfo('keyValue', (string) $keyValue);
        $this->addInfo('model', get_class($cacheByKey->getModel()));
        $this->addInfo('modelName', $cacheByKey->getModel()->getName());
        $this->addHint($hint);
    }
}
