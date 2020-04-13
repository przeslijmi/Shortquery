<?php declare(strict_types=1);

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
 * $model->getLogics()->addRule('id', 'eq', 3);
 * ```
 */
class Comp extends AnyItem
{

    /**
     * Method name (eg. `eq`, `neq`).
     *
     * @var string
     */
    private $method;

    /**
     * Parent Rule object.
     *
     * @var Rule
     */
    private $ruleParent;

    /**
     * Trigger to make comparison method silent. If set to true comparison will be empty string.
     *
     * Silence is turned off inside FuncToString class:
     * ```
     * $this->func->getRuleParent()->getComp()->setSilent();
     * ```
     *
     * @var boolean
     */
    private $silent = false;

    /**
     * Constructor.
     *
     * @param string $method Method of comparison (eg. eq, noq, gt, etc.).
     *
     * @throws ParamOtosetException When given comp. method does not exists.
     */
    public function __construct(string $method)
    {

        $this->setMethod($method);
    }

    /**
     * Getter for method name.
     *
     * @return string
     */
    public function getMethod() : string
    {

        return $this->method;
    }

    /**
     * Setter for method name (eg. `eq`, `neq`).
     *
     * @param string $method Method name (eg. `eq`, `neq`).
     *
     * @throws ParamOtosetException If this comparison method does not exists.
     * @return self
     */
    public function setMethod(string $method) : self
    {

        // Check.
        if (in_array($method, Shoq::COMPARISON_METHODS) === false) {
            throw new ParamOtosetException('compareMethod', Shoq::COMPARISON_METHODS, $method);
        }

        // Save.
        $this->method = $method;

        return $this;
    }

    /**
     * Setter for parent Rule object.
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
     * Setter for silent comparison mode (make string empty).
     *
     * @param boolean $silent Opt., true.
     *
     * @return self
     */
    public function setSilent(bool $silent = true) : self
    {

        $this->silent = $silent;

        return $this;
    }

    /**
     * Getter for silent.
     *
     * @return boolean
     */
    public function getSilent() : bool
    {

        return $this->silent;
    }
}
