<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString;

use Przeslijmi\Shortquery\Items\Vals;

/**
 * Converts Vals element into string.
 *
 * ## Usage example
 * ```
 * $vals = new Vals([ '513', 513, 'aaa' ]);
 * echo (new ValsToString($vals))->toString(); // will return '513', '513', 'aaa'
 * ```
 */
class ValsToString
{

    /**
     * Vals element to be converted to string.
     *
     * @var Vals
     */
    private $vals;

    /**
     * Context name - where are you going to use result of this `FieldToString` class?
     *
     * @var string
     */
    private $context;

    /**
     * Constructor.
     *
     * @param Vals   $vals    Vals element to be converted to string.
     * @param string $context Name of context.
     */
    public function __construct(Vals $vals, string $context = '')
    {

        $this->vals    = $vals;
        $this->context = $context;
    }

    /**
     * Converts to string.
     *
     * @return string
     */
    public function toString() : string
    {

        foreach ($this->vals->getValues() as $val) {
            $results[] = '\'' . str_replace('\'', '\\\'', $val) . '\'';
        }

        return implode(', ', $results);
    }
}
