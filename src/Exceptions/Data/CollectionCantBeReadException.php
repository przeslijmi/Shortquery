<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Data;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;

/**
 * There were problems with reading Collection.
 */
class CollectionCantBeReadException extends ClassFopException
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
    public function __construct(string $className, ?Throwable $cause = null)
    {

        // Define.
        $this->addInfo('className', $className);
        $this->addInfo('context', 'ShortqueryCreatingCollection');
        $this->addInfo('hint', 'There was an error during creation of Collection. See causes.');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}