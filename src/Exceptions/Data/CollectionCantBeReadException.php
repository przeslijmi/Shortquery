<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Data;

use Przeslijmi\Sexceptions\Sexception;

/**
 * There were problems with reading Collection.
 */
class CollectionCantBeReadException extends Sexception
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
        'collectionClass',
    ];
}
