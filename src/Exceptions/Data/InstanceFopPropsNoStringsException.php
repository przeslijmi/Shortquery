<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Data;

use Przeslijmi\Sexceptions\Sexception;

/**
 * You're trying to create instance with props that are not strings.
 */
class InstanceFopPropsNoStringsException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'You\'re trying to create instance with props that are not strings.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'className',
    ];
}
