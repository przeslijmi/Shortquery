<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;

/**
 * Converts function CONCAT to string.
 */
class FuncConcatToString extends FuncToStringParent
{

    /**
     * Converts func CONCAT to string.
     *
     * @todo No negation acceptable.
     *
     * @return string
     */
    public function toString() : string
    {

        $this->throwIfItemsCountLessThan(1);

        $items = [];

        foreach ($this->func->getItems() as $item) {
            $items[] = $this->itemToString($item);
        }

        $result = 'CONCAT(' . implode(', ', $items) . ')';

        return $result;
    }
}
