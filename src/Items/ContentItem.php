<?php declare(strict_types=1);

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
     */
    private $ruleParent;

    /**
     * Holds parent Func object.
     *
     * @var Func
     */
    private $funcParent;

    /**
     * Holds alias name for this Content Item.
     *
     * @var string
     */
    private $alias = '';

    /**
     * Setter for parent Rule.
     *
     * @param Rule $ruleParent Set given Rule as a parent of this object.
     *
     * @since  v1.0
     * @return void
     */
    public function setRuleParent(Rule $ruleParent) : void
    {

        $this->ruleParent = $ruleParent;
    }

    /**
     * Getter for parent Rule.
     *
     * @since  v1.0
     * @return Rule
     */
    public function getRuleParent() : Rule
    {

        return $this->ruleParent;
    }

    /**
     * Setter for parent Func.
     *
     * @param Func $funcParent Set given Func as a parent of this object.
     *
     * @since  v1.0
     * @return void
     */
    public function setFuncParent(Func $funcParent) : void
    {

        $this->funcParent = $funcParent;
    }

    /**
     * Getter for parent Func.
     *
     * @since  v1.0
     * @return Func
     */
    public function getFuncParent() : Func
    {

        return $this->funcParent;
    }

    /**
     * Returns true if a parent of this object is a Rule object
     *
     * @since  v1.0
     * @return boolean
     *
     * @phpcs:disable Generic.NamingConventions.CamelCapsFunctionName
     */
    public function isRuleAParent() : bool
    {

        return ( is_null($this->ruleParent) !== true );
    }

    public function setAlias(string $alias) : self
    {

        $this->alias = $alias;

        return $this;
    }

    public function getAlias() : string
    {

        return $this->alias;
    }
}
