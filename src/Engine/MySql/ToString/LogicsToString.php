<?php

namespace Przeslijmi\Shortquery\Engine\Mysql\ToString;

use Przeslijmi\Sexceptions\Exceptions\ParamWrotypeException;
use Przeslijmi\Sexceptions\Exceptions\TypeHintingFailException;
use Przeslijmi\Sivalidator\TypeHinting;

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
class LogicsToString
{

    /**
     * Collection of LogicItem elements to be converted to string.
     *
     * @var   LogicItem[]
     * @since v1.0
     */
    private $logics;

    /**
     * Constructor.
     *
     * @param LogicItem[] $logics Collection of LogicItem elements to be converted to string.
     *
     * @since v1.0
     */
    public function __construct($logics)
    {

        try {
            TypeHinting::isArrayOf($logics, 'Przeslijmi\Shortquery\Items\LogicItem');
        } catch (TypeHintingFailException $e) {
            throw new ParamWrotypeException('mysqlEngineConvLogicsToString', 'Przeslijmi\Shortquery\Items\LogicItem[]', $e->getIsInFact(), $e);
        }

        $this->logics = $logics;
    }

    /**
     * Converts to string.
     *
     * @since  v1.0
     * @return string
     */
    public function toString() : string
    {

        // lvd
        $results = [];

        // add every logic
        foreach ($this->logics as $logicItem) {
            $results[] = (new LogicToString($logicItem))->toString();
        }

        return implode(' AND ', $results);
    }

    /**
     * Converts to string that includes WHERE statement.
     *
     * @since  v1.0
     * @return string
     */
    public function toWhereString() : string
    {

        $result = $this->toString();

        if (empty($result) === false) {
            $result = ' WHERE ' . $result;
        }

        return $result;
    }
}
