<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Collection;

use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Shortquery\Data\Relation;
use Przeslijmi\Shortquery\Exceptions\Items\LogicCreationFopException;
use Przeslijmi\Shortquery\Exceptions\Items\RuleCreationFopException;
use Przeslijmi\Shortquery\Items\ContentItem;
use Przeslijmi\Shortquery\Items\LogicAnd;
use Przeslijmi\Shortquery\Items\LogicItem;
use Przeslijmi\Shortquery\Items\LogicOr;
use Przeslijmi\Shortquery\Items\Rule;
use Throwable;

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
    private $logics = [];

    /**
     * Constructor.
     *
     * @param Collection $collection Parent Collection object.
     */
    public function __construct(Collection $collection)
    {

        $this->collection = $collection;
    }

    /**
     * Getter for array of all Logics.
     *
     * @return LogicItem[]
     */
    public function get() : array
    {

        return $this->logics;
    }

    /**
     * Counts how many Logics are in Collection.
     *
     * @return integer
     */
    public function length() : int
    {

        return count($this->logics);
    }

    /**
     * Adds Logic to Collection.
     *
     * @param LogicItem ...$logicItems Array of LogicItems.
     *
     * @return self
     */
    public function add(LogicItem ...$logicItems) : self
    {

        // Work.
        foreach ($logicItems as $logicItem) {

            // Finally add.
            $this->logics[] = $logicItem;
        }

        return $this;
    }

    /**
     * Setter for adding new rule (and therefore also LogicAnd) to the model.
     *
     * @throws RuleCreationFopException When creation of Rule failed.
     * @return self
     */
    public function addRule() : self
    {

        // Try to create Rule out of fucn arguments.
        try {
            $rule = Rule::factory(...func_get_args());
        } catch (Throwable $thr) {
            throw new RuleCreationFopException([], 0, $thr);
        }

        // Create new AND Logic for this rule.
        $logic = new LogicAnd($rule);
        $logic->setCollectionParent($this->collection);

        // Add this rule to Logic.
        $this->logics[] = $logic;

        return $this;
    }

    /**
     * Setter for adding new rule "equals" (and therefore also LogicAnd) to the model.
     *
     * @return self
     */
    public function addRuleEq() : self
    {

        $this->addRule(
            ( func_get_args()[0] ?? null ),
            'eq',
            ( func_get_args()[1] ?? null )
        );

        return $this;
    }

    /**
     * Setter for adding new rule "not equals" (and therefore also LogicAnd) to the model.
     *
     * @return self
     */
    public function addRuleNeq() : self
    {

        $this->addRule(
            ( func_get_args()[0] ?? null ),
            'neq',
            ( func_get_args()[1] ?? null )
        );

        return $this;
    }

    /**
     * Setter for adding new rule LogicOr to the model.
     *
     * @param array ...$rulesDefinitions Array of arrays with rules definitions.
     *
     * @throws LogicCreationFopException When creation of Logic failed.
     * @return self
     */
    public function addLogicOr(array ...$rulesDefinitions) : self
    {

        // Lvd.
        $rules = [];

        try {

            // Create Rules.
            foreach ($rulesDefinitions as $ruleDefinition) {
                $rules[] = Rule::factory(...$ruleDefinition);
            }

            // Create logic OR from given set of Rules.
            $logic = new LogicOr(...$rules);
            $logic->setCollectionParent($this->collection);

        } catch (Throwable $thr) {
            throw new LogicCreationFopException([], 0, $thr);
        }

        $this->logics[] = $logic;

        return $this;
    }

    /**
     * Transfer logics from Relation.
     *
     * @param Relation $relation Relation between to tables.
     *
     * @return self
     */
    public function addFromRelation(Relation $relation) : self
    {

        foreach ($relation->getLogics() as $logic) {
            $this->add($logic);
        }

        return $this;
    }
}
