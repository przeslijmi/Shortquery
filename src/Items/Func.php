<?php

namespace Przeslijmi\Shortquery\Items;

use Przeslijmi\Sexceptions\Exceptions\ParamWrotypeException;
use Przeslijmi\Sexceptions\Exceptions\ParamOtosetException;
use Przeslijmi\Sexceptions\Exceptions\TypeHintingFailException;
use Przeslijmi\Sivalidator\TypeHinting;

class Func extends ContentItem
{

    private $name; // string
    private $items; // ContentItem[]

    public static function make(string $name, array $items) : Func
    {

        foreach ($items as $i => $item) {
            if (is_a($item, 'Przeslijmi\Shortquery\Items\ContentItem') === false) {
                if (is_array($item)) {
                    $items[$i] = new Vals($item);
                } else {
                    $items[$i] = new Val($item);
                }
            }
        }

        return new Func($name, $items);
    }

    public function __construct(string $name, array $items)
    {

        try {
            TypeHinting::isArrayOf($items, 'Przeslijmi\Shortquery\Items\ContentItem');
        } catch (TypeHintingFailException $e) {
            throw new ParamWrotypeException('items', 'ContentItem[]', $e->getIsInFact());
        }

        $this->name = strtolower($name);
        $this->items = $items;

        foreach ($this->items as $item) {
            $item->setFuncParent($this);
        }
    }

    public function getName() : string
    {

        return $this->name;
    }

    public function getItems() : array
    {

        return $this->items;
    }

    public function countItems() : int
    {

        return count($this->items);
    }

    public function getItem(int $itemId) : ContentItem
    {

        if (!isset($this->items[$itemId])) {
            throw (new ParamOtosetException('itemId', array_keys($this->items), $itemId))->addInfo('funcName', $this->name);
        }

        return $this->items[$itemId];
    }
}
