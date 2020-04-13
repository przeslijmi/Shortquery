<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Items;

/**
 * Logical AND connection between one or more rules.
 */
class LogicAnd extends LogicItem
{

    /**
     * Constructor.
     *
     * @param Rule $rule One or more rules (as next parameters).
     *
     * @phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
     */
    public function __construct(Rule $rule)
    {

        parent::__construct(...func_get_args());
    }
}
