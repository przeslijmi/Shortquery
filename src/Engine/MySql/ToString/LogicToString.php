<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\Mysql\ToString;

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
     * @var   LogicItem
     * @since v1.0
     */
    private $logicItem;

    /**
     * Constructor.
     *
     * @param LogicItem $logicItem LogicItem element to be converted to string.
     *
     * @since v1.0
     */
    public function __construct(LogicItem $logicItem)
    {

        $this->logicItem = $logicItem;
    }

    /**
     * Converts to string.
     *
     * @since  v1.0
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

        if (count($results) === 0) {
            return '';
        }

        $result = '(' . implode(' ' . $conjunction . ' ', $results) . ')';

        return $result;
    }
}
