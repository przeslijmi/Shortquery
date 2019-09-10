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
     * Context name - where are you going to use result of this `FieldToString` class?
     *
     * @var   string
     * @since v1.0
     */
    private $context;

    /**
     * Constructor.
     *
     * @param Val    $val     Val element to be converted to string.
     * @param string $context Name of context.
     *
     * @since v1.0
     */
    public function __construct(Val $val, string $context = '')
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

        // Construct.
        $result = '\'' . str_replace('\'', '\\\'', $this->val->getValue()) . '\'';

        // Add alias.
        if (empty($this->val->getAlias()) === false) {
            $result .= ' AS `' . $this->val->getAlias() . '`';
        }

        return $result;
    }
}
