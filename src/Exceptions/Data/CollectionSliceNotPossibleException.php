<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Data;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Collection has not enough records to make an ordered slice.
 */
class CollectionSliceNotPossibleException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Collection has not enough records to make an ordered slice.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'modelName',
        'sliceFrom',
        'sliceLength',
    ];
}
