<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Relation has FieldFrom defined but when tries to instantiate it ... fails. See causes.
 */
class RelationFieldFromIsCorrupted extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Relation has FieldFrom defined but when tries to instantiate it ... fails. See causes.';

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
