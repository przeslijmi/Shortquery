<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Data;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * Model collection class name is empty.
 */
class CollectionCantBeCreatedException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param string         $className For which collection the problem occurs.
     * @param array          $params    What was the params sent to constructor.
     * @param Throwable|null $cause     Throwable that caused the problem.
     *
     * @since v1.0
     *
     * phpcs:disable Generic.Files.LineLength
     */
    public function __construct(string $className, array $params, ?Throwable $cause = null)
    {

        // Define.
        $this->addInfo('className', $className);
        $this->addInfo('params', print_r($params, true));
        $this->addInfo('context', 'ShortqueryCreatingCollection');
        $this->addInfo('hint', 'There was an error during creation of Collection. See causes.');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
