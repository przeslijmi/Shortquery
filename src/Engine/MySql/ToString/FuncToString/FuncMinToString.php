<?php

namespace Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;

/**
 * Converts function MIN to string.
 */
class FuncMinToString extends FuncToStringParent
{

    /**
     * Converts func MIN to string.
     *
     * @since  v1.0
     * @return string
     */
    public function toString() : string
    {

        $this->throwIfItemsCountNotEquals(1);

        $result  = ' MIN(';
        $result .= $this->itemToString($this->func->getItem(0));
        $result .= ') ';

        return $result;
    }
}
