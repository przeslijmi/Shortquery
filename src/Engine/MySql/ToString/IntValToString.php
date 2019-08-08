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
     * Constructor.
     *
     * @param IntVal $val IntVal element to be converted to string.
     *
     * @since v1.0
     */
    public function __construct(IntVal $val)
    {

        $this->val = $val;
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
