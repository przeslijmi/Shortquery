<?php

namespace Przeslijmi\Shortquery;

use Przeslijmi\Sexceptions\Exceptions\ParamWrotypeException;
use Przeslijmi\Sexceptions\Exceptions\TypeHintingFailException;
use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Shortquery\Items\LogicItem;
use Przeslijmi\Sivalidator\TypeHinting;

/**
 * Abstract parent class for every engine.
 */
abstract class Engine
{

    /**
     * Array of logics.
     *
     * @var LogicItem[]
     */
    protected $logics = [];

    /**
     * Collection which is using this engine.
     *
     * @var Collection
     */
    protected $collection;

    /**
     * Setter of Collection.
     *
     * @param Collection $collection Colection.
     *
     * @since  v1.0
     * @return void
     */
    public function setCollection(Collection $collection) : void
    {

        $this->collection = $collection;
    }

    /**
     * Getter for Collection.
     *
     * @since  v1.0
     * @return Collection
     */
    public function getCollection() : Collection
    {

        return $this->collection;
    }

    /**
     * Adder for LogicItem.
     *
     * @param LogicItem[] ...$newLogics Array of LogicItems.
     *
     * @since  v1.0
     * @return void
     */
    public function addLogics(LogicItem ...$newLogics) : void
    {

        $this->logics = array_merge($this->logics, $newLogics);
    }
}
