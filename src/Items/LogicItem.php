<?php

namespace Przeslijmi\Shortquery\Items;

use Przeslijmi\Sexceptions\Exceptions\ParamWrotypeException;
use Przeslijmi\Sexceptions\Exceptions\TypeHintingFailException;
use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Sivalidator\TypeHinting;

/**
 * Abstract parent class for LogicOr and LogicAnd objects.
 */
abstract class LogicItem extends AnyItem
{

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
