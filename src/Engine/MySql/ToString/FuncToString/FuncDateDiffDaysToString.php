<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;

/**
 * Converts function DATEDIFF to string.
 */
class FuncDateDiffDaysToString extends FuncToStringParent
{

    /**
     * Func possible only for given comparison methods.
     *
     * @var string[]
     */
    protected $onlyForCompMethods = [ 'eq', 'neq' ];

    /**
     * Converts func DATEDIFF to string.
     *
     * @since  v1.0
     * @return string
     */
    public function toString() : string
    {

        $this->throwIfItemsCountNotEquals(2);

        $result  = ' DATEDIFF(';
        $result .= $this->itemToString($this->func->getItem(0));
        $result .= ', ';
        $result .= $this->itemToString($this->func->getItem(1));
        $result .= ')';

        return $result;
    }
}
