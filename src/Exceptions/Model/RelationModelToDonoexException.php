<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * There is no ModelTo in this Model.
 */
class RelationModelToDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Relation has no defined model from. Use `$relation->setModelTo()`.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'relationName',
        'relationClass',
    ];
}
