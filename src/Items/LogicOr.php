<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Items;

/**
 * Logical OR connection between one or more rules.
 */
class LogicOr extends LogicItem
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
