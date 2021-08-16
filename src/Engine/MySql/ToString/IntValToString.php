<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString;

use Przeslijmi\Shortquery\Items\IntVal;

/**
 * Converts IntVal element into string.
 *
 * ## Usage example
 * ```
 * $val = new IntVal('513');
 * echo (new IntValToString($val))->toString(); // will return `'513'`
 * ```
 */
class IntValToString
{

    /**
     * IntVal element to be converted to string.
     *
     * @var IntVal
     */
    private $val;

    /**
     * Context name - where are you going to use result of this `FieldToString` class?
     *
     * @var string
     */
    private $context;

    /**
     * Constructor.
     *
     * @param IntVal $val     IntVal element to be converted to string.
     * @param string $context Name of context.
     */
    public function __construct(IntVal $val, string $context = '')
    {

        $this->val     = $val;
        $this->context = $context;
    }

    /**
     * Converts to string.
     *
     * @return string
     */
    public function toString() : string
    {

        return (string) $this->val->getValue();
    }
}
