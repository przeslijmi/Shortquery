<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Relation;

/**
 * Relation asked to create Rule but this Rule failed to be created.
 */
class RelationFailedToCreateRule extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param array    $params   What was the params sent to constructor.
     * @param Relation $relation Relation which suffers the problem.
     */
    public function __construct(array $params, Relation $relation)
    {

        // Lvd.
        $hint  = 'Relation was created with call to create Rule. But Rule Factory ';
        $hint .= 'failed its operation - given settings for Rule were inproper. See causes.';

        // Define.
        if (count($params) > 0) {
            $this->addInfo('params', var_export($params, true));
        }
        $this->addInfo('relationName', $relation->getName());
        $this->addInfo('relationClass', get_class($relation));
        $this->addHint($hint);
    }
}
