<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\Mysql\ToString;

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
     * @var   IntVal
     * @since v1.0
     */
    private $val;

    /**
     * Context name - where are you going to use result of this `FieldToString` class?
     *
     * @var   string
     * @since v1.0
     */
    private $context;

    /**
     * Constructor.
     *
     * @param IntVal $val     IntVal element to be converted to string.
     * @param string $context Name of context.
     *
     * @since v1.0
     */
    public function __construct(IntVal $val, string $context = '')
    {

        $this->val     = $val;
        $this->context = $context;
    }

    /**
     * Converts to string.
     *
     * @since  v1.0
     * @return string
     */
    public function toString() : string
    {

        return (string) $this->val->getValue();
    }
}