<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Given collection class name is not proper proper class name (see regex below).
 */
class ModelCollectionClassNameWrosynException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Given collection class name is not proper proper class name (see regex below).';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'modelClass',
        'givenCollectionClassName',
    ];
}
