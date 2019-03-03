<?php

namespace Przeslijmi\Shortquery\Engine\Mysql\ToString;

use Przeslijmi\Shortquery\Items\Vals;

/**
 * Converts Vals element into string.
 *
 * ## Usage example
 * ```
 * $vals = new Vals('513', 513, 'aaa');
 * echo (new ValsToString($vals))->toString(); // will return `'513', '513', 'aaa'`
 * ```
 */
class ValsToString
{

    /**
     * Vals element to be converted to string.
     *
     * @var   Vals
     * @since v1.0
     */
    private $vals;

    /**
     * Constructor.
     *
     * @param Vals $vals Vals element to be converted to string.
     *
     * @since v1.0
     */
    public function __construct(Vals $vals)
    {

        $this->vals = $vals;
    }

    /**
     * Converts to string.
     *
     * @since  v1.0
     * @return string
     */
    public function toString() : string
    {

        $results = [];

        foreach ($this->vals->getValues() as $val) {
            $results[] = '\'' . str_replace('\'', '\\\'', $val) . '\'';
        }

        return implode(', ', $results);
    }
}
