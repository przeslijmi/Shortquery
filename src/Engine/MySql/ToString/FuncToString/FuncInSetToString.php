<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;

/**
 * Converts function IN SET to string.
 */
class FuncInSetToString extends FuncToStringParent
{

    /**
     * Func possible only for given comparison methods.
     *
     * @var string[]
     */
    protected $onlyForCompMethods = [ 'eq', 'neq' ];

    /**
     * Converts func `FIND_IN_SET` to string.
     *
     * @return string
     */
    public function toString() : string
    {

        $this->throwIfItemsCountNotEquals(2);
        $this->throwIfCompMethodIsInappropriate();

        $result  = ' FIND_IN_SET( ';
        $result .= $this->itemToString($this->func->getItem(0));
        $result .= ', ';
        $result .= $this->itemToString($this->func->getItem(1));
        $result .= ' )';

        return trim($result) . ' ';
    }
}
