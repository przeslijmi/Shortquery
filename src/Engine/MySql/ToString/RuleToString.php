<?php

namespace Przeslijmi\Shortquery\Engine\Mysql\ToString;

use Przeslijmi\Shortquery\Items\Rule;

/**
 * Converts Rule element into string.
 *
 * ## Usage example
 * ```
 * $rule = new Rule(new Field('name'), new Comp('eq'), new Val('john'));
 * echo (new RuleToString())->toString(); // will return `name='john'`
 * ```
 */
class RuleToString
{

    /**
     * Rule element to be converted to string.
     *
     * @var   Rule
     * @since v1.0
     */
    private $rule;

    /**
     * Constructor.
     *
     * @param Rule $rule Rule element to be converted to string.
     *
     * @since v1.0
     */
    public function __construct(Rule $rule)
    {

        $this->rule = $rule;
    }

    /**
     * Converts to string.
     *
     * @since  v1.0
     * @return string
     */
    public function toString() : string
    {

        $left = $this->rule->getLeft();
        $right = $this->rule->getRight();

        $leftIs = get_class($left);
        $rightIs = get_class($right);
        $compMethodIs = $this->rule->getComp()->getMethod();

        switch ($leftIs) {
        case 'Przeslijmi\Shortquery\Items\Field':
            $left = (new FieldToString($left))->toString();
            break;
        case 'Przeslijmi\Shortquery\Items\Val':
            $left = (new ValToString($left))->toString();
            break;
        case 'Przeslijmi\Shortquery\Items\Vals':
            $left = (new ValsToString($left))->toString();
            break;
        case 'Przeslijmi\Shortquery\Items\Func':
            $left = (new FuncToString($left))->toString();
            break;
        }

        switch ($rightIs) {
        case 'Przeslijmi\Shortquery\Items\Field':
            $right = (new FieldToString($right))->toString();
            break;
        case 'Przeslijmi\Shortquery\Items\Val':
            $right = (new ValToString($right))->toString();
            break;
        case 'Przeslijmi\Shortquery\Items\Vals':
            $right = (new ValsToString($right))->toString();
            break;
        case 'Przeslijmi\Shortquery\Items\Func':
            $right = (new FuncToString($right))->toString();
            break;
        }

        $comp = (new CompToString($this->rule->getComp()))->toString();

        return $left . $comp . $right;
    }
}
