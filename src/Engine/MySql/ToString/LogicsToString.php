<?php declare(strict_types=1);

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
 * $ruleAge = new Rule(new Field('age'), new Comp('lt'), new Val('25'));
 * $logicItem = new LogicAnd($ruleName, $ruleAge)
 * echo (new LogicItemToString($logicItem))->toString();
 * // will return
 * // (`name`='john' AND `age`<'25')
 * ```
 */
class LogicsToString
{

    /**
     * Collection of LogicItem elements to be converted to string.
     *
     * @var LogicItem[]
     */
    private $logics;

    /**
     * Context name - where are you going to use result of this `FieldToString` class?
     *
     * @var string
     */
    private $context;

    /**
     * Constructor.
     *
     * @param LogicItem[] $logics  Collection of LogicItem elements to be converted to string.
     * @param string      $context Name of context.
     *
     * @throws ParamWrotypeException On mysqlEngineConvLogicsToString.
     */
    public function __construct(array $logics, string $context = '')
    {

        try {
            TypeHinting::isArrayOf($logics, 'Przeslijmi\Shortquery\Items\LogicItem');
        } catch (TypeHintingFailException $e) {
            throw new ParamWrotypeException(
                'mysqlEngineConvLogicsToString',
                'Przeslijmi\Shortquery\Items\LogicItem[]',
                $e->getIsInFact(),
                $e
            );
        }

        $this->logics  = $logics;
        $this->context = $context;
    }

    /**
     * Converts to string.
     *
     * @return string
     */
    public function toString() : string
    {

        // Lvd.
        $results = [];

        // Add every logic.
        foreach ($this->logics as $logicItem) {
            $results[] = ( new LogicToString($logicItem) )->toString();
        }

        return implode(' AND ', $results);
    }

    /**
     * Converts to string that includes WHERE statement.
     *
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
