<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Data;

use Przeslijmi\Sexceptions\Sexception;

/**
 * There was an error during creation of Collection. See causes.
 */
class CollectionCantBeCreatedException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'There was an error during creation of Collection. See causes.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'params',
    ];
}
