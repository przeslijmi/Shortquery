<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql;

use Przeslijmi\Shortquery\Engine\MySql\ToString\CompToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\FalseValToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\FieldToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\IntValToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\LogicsToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\LogicToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\NullValToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\RuleToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\TrueValToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\ValsToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\ValToString;
use Przeslijmi\Shortquery\Exceptions\Engines\MySql\ToStringFopException;
use Przeslijmi\Shortquery\Items\AnyItem;

/**
 * Converts items to string using item-specific methods.
 */
class ToString
{

    /**
     * Converts item to string.
     *
     * @param Item|LogicItem[] $item    Item to be converted to string.
     * @param string           $context Name of context.
     *
     * @return string
     *
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity
     */
    public static function convert($item, string $context = '') : string
    {

        return self::getMaker($item, $context)->toString();
    }

    /**
     * Returns method for this particular kind of Item that converts it to string.
     *
     * @param Item|LogicItem[] $item    Item to be converted to string.
     * @param string           $context Name of context.
     *
     * @throws ToStringFopException When send Item not fits to any maker.
     * @return string
     */
    private static function getMaker($item, string $context)
    {

        if (is_array($item) === true) {
            return new LogicsToString($item, $context);
        }

        // Fast track.
        if (is_object($item) === false) {
            throw new ToStringFopException();
        }

        if (is_a($item, 'Przeslijmi\Shortquery\Items\Comp') === true) {
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
        }//end if

        throw new ToStringFopException();
    }
}
