<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;

/**
 * Converts locally existing any function to string.
 */
class FuncLocalToString extends FuncToStringParent
{

    /**
     * Converts any locally existing func to string.
     *
     * @return string
     */
    public function toString() : string
    {

        $this->throwIfItemsCountLessThan(1);

        $arguments = [];

        foreach ($this->func->getItems() as $no => $item) {

            if ($no === 0) {
                $funcName = $this->itemToString($item);
            } else {
                $arguments[] = $this->itemToString($item);
            }
        }

        $result = ' ' . trim($funcName, '\'') . ' (' . implode(', ', $arguments) . ')';

        return $result;
    }
}
