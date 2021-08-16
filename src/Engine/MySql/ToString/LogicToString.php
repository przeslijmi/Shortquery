<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString;

use Przeslijmi\Shortquery\Items\LogicItem;

/**
 * Converts LogicItem element into string.
 *
 * ## Usage example
 * ```
 * $ruleName = new Rule(new Field('name'), new Comp('eq'), new Val('john'));
 * $ruleAge = new Rule(new Field('age'), new Comp('lt'), new Val(25));
 * $logicItem = new LogicAnd($ruleName, $ruleAge)
 * echo (new LogicItemToString($logicItem))->toString();
 * // will return
 * // name='john' and age<'25'
 * ```
 */
class LogicToString
{

    /**
     * LogicItem element to be converted to string.
     *
     * @var LogicItem
     */
    private $logicItem;

    /**
     * Context name - where are you going to use result of this `FieldToString` class?
     *
     * @var string
     */
    private $context;

    /**
     * Constructor.
     *
     * @param LogicItem $logicItem LogicItem element to be converted to string.
     * @param string    $context   Name of context.
     */
    public function __construct(LogicItem $logicItem, string $context = '')
    {

        $this->logicItem = $logicItem;
        $this->context   = $context;
    }

    /**
     * Converts to string.
     *
     * @return string
     */
    public function toString() : string
    {

        $isALogicOr  = ( is_a($this->logicItem, 'Przeslijmi\Shortquery\Items\LogicOr') === true );
        $conjunction = ( ( $isALogicOr === true ) ? 'OR' : 'AND' );
        $results     = [];

        foreach ($this->logicItem->getRules() as $rule) {
            $results[] = ( new RuleToString($rule) )->toString();
        }

        $result = '(' . implode(' ' . $conjunction . ' ', $results) . ')';

        return $result;
    }
}
