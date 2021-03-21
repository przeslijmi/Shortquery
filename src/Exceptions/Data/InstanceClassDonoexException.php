<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Data;

use Przeslijmi\Sexceptions\Sexception;

/**
 * You're trying to create instance of nonexisting class.
 */
class InstanceClassDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'You\'re trying to create instance of nonexisting class.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'className',
    ];
}
