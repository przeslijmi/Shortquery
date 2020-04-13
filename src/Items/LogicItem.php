<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Items;

use Przeslijmi\Sexceptions\Exceptions\ParamWrotypeException;
use Przeslijmi\Sexceptions\Exceptions\TemporaryException;
use Przeslijmi\Sexceptions\Exceptions\TypeHintingFailException;
use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Shortquery\Items\LogicAnd;
use Przeslijmi\Shortquery\Items\Rule;
use Przeslijmi\Sivalidator\TypeHinting;

/**
 * Abstract parent class for LogicOr and LogicAnd objects.
 */
abstract class LogicItem extends AnyItem
{

    /**
     * Creates logic item collection in array.
     *
     * @throws TemporaryException When sth will go wrong.
     * @return array
     */
    public static function factory() : array
    {

        // Lvd.
        $args   = func_get_args();
        $rules  = [];
        $logics = [];

        // Convert arrays to Rules.
        foreach ($args as $id => $arg) {

            if (is_array($arg) === true) {
                $args[$id] = Rule::factory(...$arg);
            }

            if (is_a($args[$id], 'Przeslijmi\Shortquery\Items\Rule') === true) {
                $rules[] = $args[$id];
            } elseif (is_a($args[$id], 'Przeslijmi\Shortquery\Items\LogicItem') === true) {
                $logics[] = $args[$id];
            } else {
                throw new TemporaryException('Creation of rules and logics failed...');
            }
        }

        if (count($rules) > 0) {
            $logics[] = new LogicAnd(...$rules);
        }

        return $logics;
    }

    /**
     * Parent Collection object.
     *
     * @var Collection
     */
    protected $collectionParent;

    /**
     * Array with rules defined in this LogicOr / LogicAnd object.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Constructor.
     *
     * @throws ParamWrotypeException On creationOfLogicItemFromWrongElements.
     */
    public function __construct()
    {

        // Add Rule at each iteration to $this->rules repo and set their parent to this class.
        foreach (func_get_args() as $rule) {

            $rule->setLogicItemParent($this);

            $this->rules[] = $rule;
        }
    }

    /**
     * Checks if this LogicItem has any rules.
     *
     * @return boolean
     */
    public function hasRules() : bool
    {

        return (bool) count($this->rules);
    }

    /**
     * Getter for all rules inside this LogicOr/LogicAnd.
     *
     * @return boolean
     */
    public function getRules() : array
    {

        return $this->rules;
    }

    /**
     * Setter for Parent of this object.
     *
     * @param Collection $collectionParent Parent of this object.
     *
     * @return void
     */
    public function setCollectionParent(Collection $collectionParent) : void
    {

        $this->collectionParent = $collectionParent;
    }

    /**
     * Getter for Parent of this object.
     *
     * @return Collection Parent of this object.
     */
    public function getCollectionParent() : Collection
    {

        return $this->collectionParent;
    }
}
