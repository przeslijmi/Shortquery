<?php

namespace Przeslijmi\Shortquery\Items;

use Przeslijmi\Sexceptions\Exceptions\ParamWrotypeException;
use Przeslijmi\Sexceptions\Exceptions\TypeHintingFailException;
use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Sivalidator\TypeHinting;

abstract class LogicItem extends AnyItem
{

    protected $collectionParent; // Collection
    protected $rules = [];

    public function __construct()
    {

        // chk
        try {
            TypeHinting::isArrayOf(func_get_args(), 'Przeslijmi\Shortquery\Items\Rule');
        } catch (TypeHintingFailException $e) {
            throw new ParamWrotypeException('creationOfLogicItemFromWrongElements', 'Przeslijmi\Shortquery\Items\Rule[]', $e->getIsInFact(), $e);
        }

        foreach (func_get_args() as $rule) {

            $rule->setLogicItemParent($this);

            $this->rules[] = $rule;
        }
    }

    public function hasRules() : bool
    {

        return (bool)count($this->rules);
    }

    public function getRules() : array
    {

        return $this->rules;
    }

    public function setCollectionParent(Collection $collectionParent) : void
    {

        $this->collectionParent = $collectionParent;
    }

    public function getCollectionParent() : Collection
    {

        return $this->collectionParent;
    }
}
