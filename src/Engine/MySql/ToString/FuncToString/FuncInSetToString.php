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
     * Converts func IN SET to string.
     *
     * @since  v1.0
     * @return string
     */
    public function toString() : string
    {

        $negation = '';

        $this->makeCompSilent();
        $this->throwIfItemsCountNotEquals(2);
        $this->throwIfCompMethodIsInappropriate();

        if ($this->func->isRuleAParent() === true) {
            if ($this->func->getRuleParent()->getComp()->getMethod() === 'neq') {
                $negation = ' NOT';
            }
        }

        $result  = ' FIND_IN_SET( ';
        $result .= $this->itemToString($this->func->getItem(0));
        $result .= ', ';
        $result .= $this->itemToString($this->func->getItem(1));
        $result .= ') IS ' . $negation . ' ';

        return trim($result) . ' ';
    }
}
