<?php

namespace Przeslijmi\Shortquery;

use Przeslijmi\Sexceptions\Exceptions\ParamWrotypeException;
use Przeslijmi\Sexceptions\Exceptions\TypeHintingFailException;
use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Sivalidator\TypeHinting;
use Przeslijmi\Shortquery\Items\LogicItem;

abstract class Engine
{

    protected $logics = []; // LogicItem[]
    protected $collection; // Collection

    public function setCollection(Collection $collection) : void
    {

        $this->collection = $collection;
    }

    public function getCollection() : Collection
    {

        return $this->collection;
    }

    public function addLogics(LogicItem ...$newLogics) : void
    {

        $this->logics = array_merge($this->logics, $newLogics);
    }
}
