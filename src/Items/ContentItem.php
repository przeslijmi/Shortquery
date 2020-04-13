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
     * Parent Rule object.
     *
     * @var Rule
     */
    private $ruleParent;

    /**
     * Parent Func object.
     *
     * @var Func
     */
    private $funcParent;

    /**
     * Alias name for this Content Item.
     *
     * @var string
     */
    private $alias = '';

    /**
     * Setter for parent Rule.
     *
     * @param Rule $ruleParent Set given Rule as a parent of this object.
     *
     * @return void
     */
    public function setRuleParent(Rule $ruleParent) : void
    {

        $this->ruleParent = $ruleParent;
    }

    /**
     * Getter for parent Rule.
     *
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
     * @return void
     */
    public function setFuncParent(Func $funcParent) : void
    {

        $this->funcParent = $funcParent;
    }

    /**
     * Getter for parent Func.
     *
     * @return Func
     */
    public function getFuncParent() : Func
    {

        return $this->funcParent;
    }

    /**
     * Returns true if a parent of this object is a Rule object
     *
     * @return boolean
     *
     * @phpcs:disable Generic.NamingConventions.CamelCapsFunctionName
     */
    public function isRuleAParent() : bool
    {

        return ( is_null($this->ruleParent) !== true );
    }

    /**
     * Setter for alias name for this Content Item.
     *
     * @param string $alias Alias name for this Content Item.
     *
     * @return self
     */
    public function setAlias(string $alias) : self
    {

        // Save.
        $this->alias = $alias;

        return $this;
    }

    /**
     * Getter for alias name for this Content Item.
     *
     * @return string
     */
    public function getAlias() : string
    {

        return $this->alias;
    }
}
