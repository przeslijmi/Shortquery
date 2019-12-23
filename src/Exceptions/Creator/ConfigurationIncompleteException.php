<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Creator;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * Creator can't do nothing because configuration is corrupted (false).
 */
class ConfigurationIncompleteException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param Exception|null $cause     Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(?Exception $cause = null)
    {

        // Lvd
        $needed = [
            'JSON key: `settings->vendor` as a string',
            'JSON key: `settings->app` as a string',
            'JSON key: `settings->srcDir` as a string with URI to existing directory',
            'JSON key: `models` as an array',
        ];

        // Lvd hint.
        $hint  = 'Configuration file has to be JSON format, and has at least: ';
        $hint .= PHP_EOL . '- ';
        $hint .= implode(', ' . PHP_EOL . '- ', $needed);
        $hint .= PHP_EOL;
        $hint .= 'At least one from above is missing or has wrong type.';

        // Define.
        $this->addInfo('context', 'CreatorHasNoConfigurationToStartWork');
        $this->addInfo('hint', $hint);

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
