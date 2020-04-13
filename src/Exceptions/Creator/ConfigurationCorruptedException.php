<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Creator;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * Creator can't do nothing because configuration is corrupted (false).
 */
class ConfigurationCorruptedException extends ClassFopException
{

    /**
     * Constructor.
     */
    public function __construct()
    {

        // Lvd.
        $hint  = 'Configuration file has to be JSON format, readable for PHP. ';
        $hint .= 'File is locked for read or has corrupted contents.';

        // Define.
        $this->addInfo('context', 'CreatorHasNoConfigurationToStartWork');
        $this->addHint($hint);
    }
}
