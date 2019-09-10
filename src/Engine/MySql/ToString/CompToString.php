<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\Mysql\ToString;

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
     * @var   Comp
     * @since v1.0
     */
    private $comp;

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
     * @param Comp   $comp    Comp element to be converted to string.
     * @param string $context Name of context.
     *
     * @since v1.0
     */
    public function __construct(Comp $comp, string $context = '')
    {

        $this->comp    = $comp;
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

        // This can be silenced by.
        if ($this->comp->getSilent() === true) {
            return '';
        }

        switch ($this->comp->getMethod()) {
            case 'eq':
                $result = '=';
            break;

            case 'neq':
                $result = '!=';
            break;

            case 'leq':
                $result = '<=';
            break;

            case 'is':
                $result = ' IS ';
            break;

            case 'nis':
                $result = ' IS NOT ';
            break;
        }

        return $result;
    }
}
