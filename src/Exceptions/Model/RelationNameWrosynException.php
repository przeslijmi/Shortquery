<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Given name of Relation is not proper. See causes.
 */
class RelationNameWrosynException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Given name of Relation is not proper. See causes.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'modelClass',
        'givenName',
    ];
}
