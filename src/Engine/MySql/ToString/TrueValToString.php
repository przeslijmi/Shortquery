<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString;

use Przeslijmi\Shortquery\Items\TrueVal;

/**
 * Converts TrueVal element into string.
 *
 * ## Usage example
 * ```
 * $val = new TrueVal();
 * echo (new TrueValToString($val))->toString(); // will return TRUE
 * ```
 */
class TrueValToString
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

        return 'TRUE';
    }
}
