<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Items;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Sexceptions\Sexception;
use Przeslijmi\Shortquery\Items\Func;
use Przeslijmi\Shortquery\Items\IntVal;
use Przeslijmi\Shortquery\Items\LogicItem;
use Przeslijmi\Shortquery\Items\NullVal;
use Przeslijmi\Shortquery\Items\Val;
use Przeslijmi\Shortquery\Items\TrueVal;

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
     * @since  v1.0
     * @throws MethodFopException On creationOfCompFailed.
     * @return Rule
     */
    public static function factory($left, $comp = null, $right = null) : Rule
    {

        // In only left is given.
        if (func_num_args() === 1) {
            $comp = 'eq';
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
        // } else {
            // @todo Add throw wrotype (as below) here.
        }

        // If $comp is not a Comp - then try create one.
        if (is_a($comp, 'Przeslijmi\Shortquery\Items\Comp') === false) {
            try {
                $comp = new Comp($comp);
            } catch (Sexception $e) {
                throw (new MethodFopException('creationOfCompFailed', $e))->addInfo('syntax', $comp);
            }
        }

        // If $right is not a ContentItem alreaty - then try to create Func<in> or value.
        if (is_a($right, 'Przeslijmi\Shortquery\Items\ContentItem') === false) {
            if (is_array($right) && count($right) === 2 && isset($right[1]) && is_array($right[1])) {
                $right = Func::factory(...$right);
            } elseif (is_array($right) === true) {
                $right = Func::factory('in', $right);
            } elseif (is_int($right) === true) {
                $right = new IntVal($right);
            } elseif (is_null($right) === true) {
                $right = new NullVal();
            } elseif ($right === true) {
                $right = new TrueVal();
            } else {
                $right = new Val($right);
            }
            // @todo Include try catch commands here.
        }

        return new Rule($left, $comp, $right);
    }

    public static function factoryWrapped($left, $comp = null, $right = null) : LogicItem
    {

        if (func_num_args() === 1) {
            return new LogicAnd(self::factory($left));
        } elseif (func_num_args() === 2) {
            return new LogicAnd(self::factory($left, $comp));
        } elseif (func_num_args() === 3) {
            return new LogicAnd(self::factory($left, $comp, $right));
        }
    }

    /**
     * Constructor.
     *
     * @param ContentItem $left  Left item of comparison.
     * @param Comp        $comp  Comparison itself.
     * @param ContentItem $right Right item of comparison.
     *
     * @since v1.0
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
     * @since  v1.0
     * @return ContentItem Left item of comparison.
     */
    public function getLeft() : ContentItem
    {

        return $this->left;
    }

    /**
     * Getter for comparison item
     *
     * @since  v1.0
     * @return Comp Comparison item
     */
    public function getComp() : Comp
    {

        return $this->comp;
    }

    /**
     * Getter for Right item of comparison.
     *
     * @since  v1.0
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
     * @since  v1.0
     * @return void
     */
    public function setLogicItemParent(LogicItem $logicItemParent) : void
    {

        $this->logicItemParent = $logicItemParent;
    }

    /**
     * Getter for Parent of this object.
     *
     * @since  v1.0
     * @return ContentItem Parent of this object.
     */
    public function getLogicItemParent() : LogicItem
    {

        return $this->logicItemParent;
    }
}
