<?php

namespace Przeslijmi\Shortquery\Items;

use Przeslijmi\Shortquery\Shoq;
use Przeslijmi\Shortquery\Items\Rule;
use Przeslijmi\Sexceptions\Exceptions\ParamOtosetException;

/**
 * Comparison method between two ContentItems (eg. field and value).
 *
 * ## Usage example
 * ```
 * // comparison is the middle `eq` element meaning `eguals`.
 * $model->addRule('id', 'eq', 3);
 * ```
 */
class Comp extends AnyItem
{

    /**
     * Method name (eg. eq, neq).
     *
     * @var String
     * @since v1.0
     */
    private $method;

    /**
     * Parent Rule object.
     *
     * @var Rule
     * @since v1.0
     */
    private $ruleParent; // Rule

    /**
     * Trigger to make comparison method silent. If set to true comparison will be empty string.
     *
     * Silence is turned off inside FuncToString class:
     * ```
     * $this->func->getRuleParent()->getComp()->setSilent();
     * ```
     *
     * @var boolean
     * @since v1.0
     */
    private $silent = false;

    /**
     * Constructor.
     *
     * @param string $method Method of comparison (eg. eq, noq, gt, etc.).
     * @since v1.0
     * @throws ParamOtosetException
     */
    public function __construct(string $method)
    {

        // check
        if (!in_array($method, Shoq::COMPARISON_METHODS)) {
            throw new ParamOtosetException('compareMethod', Shoq::COMPARISON_METHODS, $method);
        }

        $this->method = $method;
    }

    /**
     * Getter for method name.
     *
     * @since v1.0
     * @return string
     */
    public function getMethod() : string
    {

        return $this->method;
    }

    /**
     * Setter for parent Rule object.
     *
     * @param Rule $ruleParent [description]
     * @since v1.0
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
     * @since v1.0
     */
    public function getRuleParent() : Rule
    {

        return $this->ruleParent();
    }

    /**
     * Setter for silent comparison mode (make string empty).
     *
     * @param bool $silent (opt., true)
     * @since v1.0
     * @return void
     */
    public function setSilent(bool $silent = true) : void
    {

        $this->silent = $silent;
    }

    /**
     * Getter for silent.
     *
     * @return bool
     * @since v1.0
     */
    public function getSilent() : bool
    {

        return $this->silent;
    }
}
