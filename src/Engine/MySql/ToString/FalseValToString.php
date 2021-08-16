<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString;

use Przeslijmi\Shortquery\Items\FalseVal;

/**
 * Converts TrueVal element into string.
 *
 * ## Usage example
 * ```
 * $val = new FalseVal();
 * echo (new FalseValToString($val))->toString(); // will return FALSE
 * ```
 */
class FalseValToString
{

    /**
     * Context name - where are you going to use result of this `FieldToString` class?
     *
     * @var string
     */
    private $context;

    /**
     * Constructor.
     *
     * @param string $context Name of context.
     */
    public function __construct(string $context = '')
    {

        $this->context = $context;
    }

    /**
     * Converts to string.
     *
     * @return string
     */
    public function toString() : string
    {

        return 'FALSE';
    }
}
