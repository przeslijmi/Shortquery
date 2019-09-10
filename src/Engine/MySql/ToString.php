<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql;

use Przeslijmi\Shortquery\Engine\Mysql\ToString\AggregationToString;
use Przeslijmi\Shortquery\Engine\Mysql\ToString\CompToString;
use Przeslijmi\Shortquery\Engine\Mysql\ToString\FieldToString;
use Przeslijmi\Shortquery\Engine\Mysql\ToString\FuncToString;
use Przeslijmi\Shortquery\Engine\Mysql\ToString\IntValToString;
use Przeslijmi\Shortquery\Engine\Mysql\ToString\LogicsToString;
use Przeslijmi\Shortquery\Engine\Mysql\ToString\LogicToString;
use Przeslijmi\Shortquery\Engine\Mysql\ToString\NullValToString;
use Przeslijmi\Shortquery\Engine\Mysql\ToString\TrueValToString;
use Przeslijmi\Shortquery\Engine\Mysql\ToString\FalseValToString;
use Przeslijmi\Shortquery\Engine\Mysql\ToString\RuleToString;
use Przeslijmi\Shortquery\Engine\Mysql\ToString\ValsToString;
use Przeslijmi\Shortquery\Engine\Mysql\ToString\ValToString;
use Przeslijmi\Shortquery\Items\AnyItem;

class ToString
{

    public static function toString($item, string $context = '') : string
    {

        return self::getMaker($item, $context)->toString();
    }

    private static function getMaker($item, string $context)
    {

        if (is_array($item) === true) {
            return new LogicsToString($item, $context);
        }

        if (is_object($item) === false) {
            throw new \Exception('ups');
        }

        if (is_a($item, 'Przeslijmi\Shortquery\Items\Aggregation') === true) {
            return new AggregationToString($item, $context);

        } elseif (is_a($item, 'Przeslijmi\Shortquery\Items\Comp') === true) {
            return new CompToString($item, $context);

        } elseif (is_a($item, 'Przeslijmi\Shortquery\Items\Field') === true) {
            return new FieldToString($item, $context);

        } elseif (is_a($item, 'Przeslijmi\Shortquery\Items\Func') === true) {
            return new FuncToString($item, $context);

        } elseif (is_a($item, 'Przeslijmi\Shortquery\Items\IntVal') === true) {
            return new IntValToString($item, $context);

        } elseif (is_a($item, 'Przeslijmi\Shortquery\Items\LogicItem') === true) {
            return new LogicToString($item, $context);

        } elseif (is_a($item, 'Przeslijmi\Shortquery\Items\NullVal') === true) {
            return new NullValToString($context);

        } elseif (is_a($item, 'Przeslijmi\Shortquery\Items\Rule') === true) {
            return new RuleToString($item, $context);

        } elseif (is_a($item, 'Przeslijmi\Shortquery\Items\TrueVal') === true) {
            return new TrueValToString($context);

        } elseif (is_a($item, 'Przeslijmi\Shortquery\Items\FalseVal') === true) {
            return new FalseValToString($context);

        } elseif (is_a($item, 'Przeslijmi\Shortquery\Items\Val') === true) {
            return new ValToString($item, $context);

        } elseif (is_a($item, 'Przeslijmi\Shortquery\Items\Vals') === true) {
            return new ValsToString($item, $context);
        }

        throw new \Exception('ups');
    }
}

