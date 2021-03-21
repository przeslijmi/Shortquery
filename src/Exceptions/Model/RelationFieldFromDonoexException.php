<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * There is no FieldFrom in this Model.
 */
class RelationFieldFromDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Relation has no defined model from. Use `$relation->setFieldFrom()`.';

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
