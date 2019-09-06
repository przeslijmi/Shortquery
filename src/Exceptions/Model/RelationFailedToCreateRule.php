<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Relation;
use Throwable;

/**
 * Relation asked to create Rule but this Rule failed to be created.
 */
class RelationFailedToCreateRule extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param array          $params   What was the params sent to constructor.
     * @param Relation       $relation Relation which suffers the problem.
     * @param Throwable|null $cause    Throwable that caused the problem.
     *
     * @since v1.0
     *
     * phpcs:disable Generic.Files.LineLength
     */
    public function __construct(array $params, Relation $relation, ?Throwable $cause = null)
    {

        // Define.
        if (count($params) > 0) {
            $this->addInfo('params', print_r($params, true));
        }
        $this->addInfo('relationName', $relation->getName());
        $this->addInfo('relationClass', get_class($relation));
        $this->addHint('Relation was created with call to create Rule. But Rule Factory failed its operation - given settings for Rule were inproper. See causes.');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
