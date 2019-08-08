<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Collection;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Shortquery\Data\Relation;
use Przeslijmi\Shortquery\Items\LogicAnd;
use Przeslijmi\Shortquery\Items\LogicItem;
use Przeslijmi\Shortquery\Items\LogicOr;
use Przeslijmi\Shortquery\Items\Rule;

/**
 * Subclass for Collection object - contains logics.
 */
class Logics
{

    /**
     * Parent Collection object.
     *
     * @var Collection
     */
    private $collection;

    /**
     * Array of all logics defined for this Collection.
     *
     * @var LogicItem[]
     */
    public $logics = [];

    /**
     * Constructor.
     *
     * @param Collection $collection Parent Collection object.
     *
     * @since v1.0
     */
    public function __construct(Collection $collection)
    {

        $this->collection = $collection;
    }

    /**
     * Getter for array of all logics.
     *
     * @since  v1.0
     * @return LogicItem[]
     */
    public function get() : array
    {

        return $this->logics;
    }

    public function length() : int
    {

        return count($this->logics);
    }

    public function add() : self
    {

        foreach (func_get_args() as $logicItem) {
            $this->logics[] = $logicItem;
        }

        return $this;
    }

    /**
     * Setter for adding new rule (and therefore also LogicAnd) to the model.
     *
     * @since  v1.0
     * @return self
     * @throws MethodFopException When creation of Rule have failed.
     */
    public function addRule() : self
    {

        try {
            $rule = Rule::factory(...func_get_args());
        } catch (Exception $e) {
            throw ( new MethodFopException('creationOfRuleFailed', $e) )
                ->addInfos(func_get_args(), 'ruleArgs');
        }

        $logic = new LogicAnd($rule);
        $logic->setCollectionParent($this->collection);

        $this->logics[] = $logic;

        return $this;
    }

    /**
     * Setter for adding new rule "equals" (and therefore also LogicAnd) to the model.
     *
     * @since  v1.0
     * @return self
     * @throws MethodFopException When creation of Rule have failed.
     */
    public function addRuleEq() : self
    {

        try {
            $ruleArgs = [ func_get_arg(0), 'eq', func_get_arg(1) ];
            $rule     = Rule::factory(...$ruleArgs);
        } catch (Sexception $e) {
            throw ( new MethodFopException('creationOfRuleFailed', $e) )
                ->addInfos($ruleArgs, 'ruleArgs');
        }

        $logic = new LogicAnd($rule);
        $logic->setCollectionParent($this->collection);

        $this->logics[] = $logic;

        return $this;
    }

    /**
     * Setter for adding new rule "not equals" (and therefore also LogicAnd) to the model.
     *
     * @since  v1.0
     * @return self
     * @throws MethodFopException When creation of Rule have failed.
     */
    public function addRuleNeq() : self
    {

        try {
            $ruleArgs = [ func_get_arg(0), 'neq', func_get_arg(1) ];
            $rule     = Rule::factory(...$ruleArgs);
        } catch (Sexception $e) {
            throw ( new MethodFopException('creationOfRuleFailed', $e) )
                ->addInfos($ruleArgs, 'ruleArgs');
        }

        $logic = new LogicAnd($rule);
        $logic->setCollectionParent($this->collection);

        $this->logics[] = $logic;

        return $this;
    }

    /**
     * Setter for adding new rule LogicOr to the model.
     *
     * @param array ...$rulesDefinitions Array of arrays with rules definitions.
     *
     * @since  v1.0
     * @return self
     */
    public function addLogicOr(array ...$rulesDefinitions) : self
    {

        foreach ($rulesDefinitions as $ruleDefinition) {
            $rules[] = Rule::factory(...$ruleDefinition);
        }

        $logic = new LogicOr(...$rules);
        $logic->setCollectionParent($this->collection);

        $this->logics[] = $logic;

        return $this;
    }

    public function addFromRelation(Relation $relation) : self
    {

        foreach ($relation->getLogics() as $logic) {
            $this->add($logic);
        }

        return $this;
    }
}
