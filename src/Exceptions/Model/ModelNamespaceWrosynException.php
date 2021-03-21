<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Given namespace is not proper proper model namespace (see regex).
 */
class ModelNamespaceWrosynException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Given namespace is not proper proper model namespace (see regex).';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'modelClass',
        'givenNamespace',
    ];
}
