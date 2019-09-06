<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * Rule could not be created because factory failed.
 */
class RuleFactoryFailedException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param array          $params What was the params sent to constructor.
     * @param Throwable|null $cause  Throwable that caused the problem.
     *
     * @since v1.0
     *
     * phpcs:disable Generic.Files.LineLength
     */
    public function __construct(array $params, ?Throwable $cause = null)
    {

        // Define.
        if (count($params) > 0) {
            $this->addInfo('params', print_r($params, true));
        }
        $this->addHint('Rule Factory failed its operation - given settings for Rule were inproper. See causes.');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
