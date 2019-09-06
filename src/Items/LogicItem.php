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

    public static function factory()
    {

        $args   = func_get_args();
        $rules  = [];
        $logics = [];

        // Convert arrays to Rules.
        foreach ($args as $id => $arg) {

            if (is_array($arg) === true) {
                $args[$id] = Rule::factory(...$arg);
            } else {
                throw new TemporaryException('You asked for strange logics...');
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
     * @since  v1.0
     * @throws ParamWrotypeException On creationOfLogicItemFromWrongElements.
     */
    public function __construct()
    {

        // Chk.
        try {
            TypeHinting::isArrayOf(func_get_args(), 'Przeslijmi\Shortquery\Items\Rule');
        } catch (TypeHintingFailException $e) {
            throw new ParamWrotypeException(
                'creationOfLogicItemFromWrongElements',
                'Przeslijmi\Shortquery\Items\Rule[]',
                $e->getIsInFact(),
                $e
            );
        }

        // Add Rule at each iteration to $this->rules repo and set their parent to this class.
        foreach (func_get_args() as $rule) {

            $rule->setLogicItemParent($this);

            $this->rules[] = $rule;
        }
    }

    /**
     * Checks if this LogicItem has any rules.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasRules() : bool
    {

        return (bool) count($this->rules);
    }

    /**
     * Getter for all rules inside this LogicOr/LogicAnd.
     *
     * @since  v1.0
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
     * @since  v1.0
     * @return void
     */
    public function setCollectionParent(Collection $collectionParent) : void
    {

        $this->collectionParent = $collectionParent;
    }

    /**
     * Getter for Parent of this object.
     *
     * @since  v1.0
     * @return Collection Parent of this object.
     */
    public function getCollectionParent() : Collection
    {

        return $this->collectionParent;
    }
}
