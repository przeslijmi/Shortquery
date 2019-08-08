<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\Mysql\ToString;

use Przeslijmi\Shortquery\Items\Val;

/**
 * Converts Val element into string.
 *
 * ## Usage example
 * ```
 * $val = new Val('513');
 * echo (new ValToString($val))->toString(); // will return `'513'`
 * ```
 */
class ValToString
{

    /**
     * Val element to be converted to string.
     *
     * @var   Val
     * @since v1.0
     */
    private $val;

    /**
     * Constructor.
     *
     * @param Val $val Val element to be converted to string.
     *
     * @since v1.0
     */
    public function __construct(Val $val)
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

        return '\'' . str_replace('\'', '\\\'', $this->val->getValue()) . '\'';
    }
}
