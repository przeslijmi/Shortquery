<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Engines\MySql;

use Przeslijmi\Sexceptions\Sexception;

/**
 * This variable can not be transferred to string by this class.
 */
class ToStringFopException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'This variable can not be transferred to string by this class.';
}
