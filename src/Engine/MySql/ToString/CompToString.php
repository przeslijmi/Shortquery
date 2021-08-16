<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString;

use Przeslijmi\Shortquery\Items\Comp;

/**
 * Converts Comp element into string.
 *
 * ## Usage example
 * ```
 * $comp = new Comp('eq');
 * echo (new CompToString($comp))->toString(); // will return `=` (equal sign)
 * ```
 */
class CompToString
{

    /**
     * Comp element to be converted to string.
     *
     * @var Comp
     */
    private $comp;

    /**
     * Context name - where are you going to use result of this `FieldToString` class?
     *
     * @var string
     */
    private $context;

    /**
     * Dictionary of possible comparison methods.
     *
     * @var array
     */
    private $compsDict = [
        'eq' => '=',
        'neq' => '!=',
        'gt' => '>',
        'geq' => '>=',
        'leq' => '<=',
        'lt' => '<',
        'is' => ' IS ',
        'nis' => ' IS NOT ',
        'lk' => ' LIKE ',
        'nlk' => ' NOT LIKE ',
    ];

    /**
     * Constructor.
     *
     * @param Comp   $comp    Comp element to be converted to string.
     * @param string $context Name of context.
     */
    public function __construct(Comp $comp, string $context = '')
    {

        $this->comp    = $comp;
        $this->context = $context;
    }

    /**
     * Converts to string.
     *
     * @return string
     */
    public function toString() : string
    {

        // This can be silenced by.
        if ($this->comp->getSilent() === true) {
            return '';
        }

        // Get result.
        $result = $this->compsDict[$this->comp->getMethod()];

        return $result;
    }
}
