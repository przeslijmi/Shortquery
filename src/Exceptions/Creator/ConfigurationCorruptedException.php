<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Creator;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * Creator can't do nothing because configuration is corrupted (false).
 */
class ConfigurationCorruptedException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param Exception|null $cause Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(?Exception $cause = null)
    {

        // Lvd.
        $hint  = 'Configuration file has to be JSON format, readable for PHP. ';
        $hint .= 'File is locked for read or has corrupted contents.';

        // Define.
        $this->addInfo('context', 'CreatorHasNoConfigurationToStartWork');
        $this->addInfo('hint', $hint);

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
