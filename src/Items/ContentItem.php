<?php

namespace Przeslijmi\Shortquery\Items;

use Przeslijmi\Shortquery\Items\Rule;
use Przeslijmi\Shortquery\Items\Func;

/**
 * Abstract parent for all Content Items (Val, Vals, Func, Field).
 */
abstract class ContentItem extends AnyItem
{

    /**
     * Holds parent Rule object.
     *
     * @var Rule
     * @since v1.0
     */
    private $ruleParent;

    /**
     * Holds parent Func object.
     *
     * @var Func
     * @since v1.0
     */
    private $funcParent;

    /**
     * Setter for parent Rule.
     *
     * @param Rule $ruleParent
     * @return void
     * @since v1.0
     */
    public function setRuleParent(Rule $ruleParent) : void
    {

        $this->ruleParent = $ruleParent;
    }

    /**
     * Getter for parent Rule.
     *
     * @return Rule
     * @since v1.0
     */
    public function getRuleParent() : Rule
    {

        return $this->ruleParent;
    }

    /**
     * Setter for parent Func.
     *
     * @param Func $funcParent
     * @return void
     * @since v1.0
     */
    public function setFuncParent(Func $funcParent) : void
    {

        $this->funcParent = $funcParent;
    }

    /**
     * Getter for parent Func.
     *
     * @return Func
     * @since v1.0
     */
    public function getFuncParent() : Func
    {

        return $this->funcParent;
    }

    public function isRuleAParent() : bool
    {

        return (is_null($this->ruleParent) !== true);
    }
}
