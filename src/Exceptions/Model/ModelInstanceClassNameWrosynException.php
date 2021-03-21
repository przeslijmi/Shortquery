<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Given instance class name is not proper proper class name (see regex)
 */
class ModelInstanceClassNameWrosynException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Given instance class name is not proper proper class name (see regex).';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'modelClass',
        'givenInstanceClassName',
    ];
}
