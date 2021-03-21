<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Sexception;

/**
 * When sent components are inproper.
 */
class LogicWrongComponentsException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'When sent components are inproper.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'needed',
    ];
}
