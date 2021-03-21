<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Items;

use Przeslijmi\Shortquery\Exceptions\Items\RuleCreationFopException;
use Przeslijmi\Shortquery\Items\Func;
use Przeslijmi\Shortquery\Items\IntVal;
use Przeslijmi\Shortquery\Items\LogicItem;
use Przeslijmi\Shortquery\Items\NullVal;
use Przeslijmi\Shortquery\Items\TrueVal;
use Przeslijmi\Shortquery\Items\Val;
use Throwable;

/**
 * Rule item - left item is in relation item (Comp) to right item.
 */
class Rule extends AnyItem
{

    /**
     * Parent of this object.
     *
     * @var LogicItem
     */
    private $logicItemParent;

    /**
     * Left item.
     *
     * @var ContentItem
     */
    private $left;

    /**
     * Comparison item.
     *
     * @var Comp
     */
    private $comp;

    /**
     * Right item.
     *
     * @var ContentItem
     */
    private $right;

    /**
     * Static factory method with many possible input types.
     *
     * @param scalar|ContentItem      $left  Left item of comparison.
     * @param scalar|Comp             $comp  Comparison itself.
     * @param null|scalar|ContentItem $right Right item of comparison.
     *
     * @throws RuleCreationFopException When creation of Rule failed.
     * @return Rule
     *
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity
     */
    public static function factory($left, $comp = null, $right = null) : Rule
    {

        try {

            // In only left is given.
            if (func_num_args() === 1) {
                $comp  = 'eq';
                $right = true;
            }

            // By default use `eq` comparison.
            if (func_num_args() === 2) {
                $right = $comp;
                $comp  = 'eq';
            }

            // If $left is not a ContentItem - then create Field ContentItem
            // cause typical comparison is <field> equals <value>.
            if (is_a($left, 'Przeslijmi\Shortquery\Items\ContentItem') === false) {
                if (is_string($left) === true) {
                    $left = Field::factory($left);
                } elseif (is_array($left) === true) {
                    $left = Func::factory(...$left);
                }
            }

            // If $comp is not a Comp - then try create one.
            if (is_a($comp, 'Przeslijmi\Shortquery\Items\Comp') === false) {
                $comp = new Comp($comp);
            }

            // If $right is not a ContentItem alreaty - then try to create Func<in> or value.
            if (is_a($right, 'Przeslijmi\Shortquery\Items\ContentItem') === false) {
                if (is_array($right) === true
                    && count($right) === 2
                    && isset($right[1]) === true
                    && is_array($right[1]) === true
                ) {
                    $right = Func::factory(...$right);
                } elseif (is_array($right) === true) {
                    $right = Func::factory('in', $right);
                } elseif (is_int($right) === true) {
                    $right = new IntVal($right);
                } elseif (is_null($right) === true) {
                    $right = new NullVal();
                } elseif ($right === true) {
                    $right = new TrueVal();
                } elseif (is_string($right) === true && substr($right, 0, 1) === '`' && substr($right, -1) === '`') {
                    $right = Field::factory($right);
                } else {
                    $right = new Val($right);
                }
            }//end if

            // Create rule.
            $rule = new Rule($left, $comp, $right);

        } catch (Throwable $thr) {
            throw new RuleCreationFopException([], 0, $thr);
        }//end try

        return $rule;
    }

    /**
     * Static factory method with many possible input types delivering LogicItem with Rule inside.
     *
     * @param scalar|ContentItem      $left  Left item of comparison.
     * @param scalar|Comp             $comp  Comparison itself.
     * @param null|scalar|ContentItem $right Right item of comparison.
     *
     * @throws RuleCreationFopException When creation of Rule failed.
     * @return LogicItem
     */
    public static function factoryWrapped($left, $comp = null, $right = null) : LogicItem
    {

        try {

            if (func_num_args() === 1) {
                return new LogicAnd(self::factory($left));
            } elseif (func_num_args() === 2) {
                return new LogicAnd(self::factory($left, $comp));
            } else {
                return new LogicAnd(self::factory($left, $comp, $right));
            }

        } catch (Throwable $thr) {
            throw new RuleCreationFopException([], 0, $thr);
        }
    }

    /**
     * Constructor.
     *
     * @param ContentItem $left  Left item of comparison.
     * @param Comp        $comp  Comparison itself.
     * @param ContentItem $right Right item of comparison.
     *
     * @throws RuleCreationFopException When creation of Rule failed.
     */
    public function __construct(ContentItem $left, Comp $comp, ContentItem $right)
    {

        $this->left  = $left;
        $this->comp  = $comp;
        $this->right = $right;

        $this->left->setRuleParent($this);
        $this->comp->setRuleParent($this);
        $this->right->setRuleParent($this);
    }

    /**
     * Getter for Left item of comparison.
     *
     * @return ContentItem Left item of comparison.
     */
    public function getLeft() : ContentItem
    {

        return $this->left;
    }

    /**
     * Getter for comparison item
     *
     * @return Comp Comparison item
     */
    public function getComp() : Comp
    {

        return $this->comp;
    }

    /**
     * Getter for Right item of comparison.
     *
     * @return ContentItem Right item of comparison.
     */
    public function getRight() : ContentItem
    {

        return $this->right;
    }

    /**
     * Setter for Parent of this object.
     *
     * @param LogicItem $logicItemParent Parent of this object.
     *
     * @return void
     */
    public function setLogicItemParent(LogicItem $logicItemParent) : void
    {

        $this->logicItemParent = $logicItemParent;
    }

    /**
     * Getter for Parent of this object.
     *
     * @return ContentItem Parent of this object.
     */
    public function getLogicItemParent() : LogicItem
    {

        return $this->logicItemParent;
    }
}
