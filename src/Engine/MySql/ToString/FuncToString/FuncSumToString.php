<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;

/**
 * Converts function SUM to string.
 */
class FuncSumToString extends FuncToStringParent
{

    /**
     * Converts func SUM to string.
     *
     * @return string
     */
    public function toString() : string
    {

        $this->throwIfItemsCountNotEquals(1);

        $result  = 'SUM(';
        $result .= $this->itemToString($this->func->getItem(0));
        $result .= ')';

        return $result;
    }
}
