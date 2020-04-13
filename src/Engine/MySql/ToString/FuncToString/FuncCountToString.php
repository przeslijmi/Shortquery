<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;

/**
 * Converts function COUNT to string.
 */
class FuncCountToString extends FuncToStringParent
{

    /**
     * Converts func COUNT to string.
     *
     * @todo No negation acceptable.
     *
     * @return string
     */
    public function toString() : string
    {

        $items = [];

        foreach ($this->func->getItems() as $item) {
            $items[] = $this->itemToString($item);
        }

        if (count($items) === 0) {
            $items[] = '*';
        }

        $result = 'COUNT(' . implode(', ', $items) . ')';

        return $result;
    }
}
