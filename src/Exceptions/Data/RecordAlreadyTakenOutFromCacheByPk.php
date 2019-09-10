<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Data;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\CacheByPk;

/**
 * Record was already taken out from CacheByPk instance.
 */
class RecordAlreadyTakenOutFromCacheByPk extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param string         $className For which Collection problem occurs.
     * @param array          $params    What was params sent to constructor.
     * @param Throwable|null $cause     Throwable that caused problem.
     *
     * @since v1.0
     *
     * phpcs:disable Generic.Files.LineLength
     */
    public function __construct($pkValue, CacheByPk $cacheByPk, ?Throwable $cause = null)
    {

        // Define.
        $this->addInfo('pkValue', (string) $pkValue);
        $this->addInfo('model', get_class($cacheByPk->getModel()));
        $this->addInfo('modelName', $cacheByPk->getModel()->getName());
        $this->addInfo('hint', 'Primary key has been already taken from this cache. If you want to get from cache more than one - use `get()`, not `getOnce()` method.');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
