<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Sexception;

/**
 * When inapropriate comp method is given.
 */
class FuncToStringInappropriateCompMethodException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'When inapropriate comp method is given.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'funcName',
        'properCompMethods',
        'usedCompMethod',
    ];
}
