<?php

namespace Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;

class FuncBetweenToString extends FuncToStringParent
{

    protected $onlyForCompMethods = [ 'eq', 'neq' ];

    /**
     * Converts func BETWEEN to string.
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

        if ($this->func->isRuleAParent()) {
            if ($this->func->getRuleParent()->getComp()->getMethod() === 'neq') {
                $negation = ' NOT';
            }
        }

        $result = $negation . ' BETWEEN ';
        $result .= $this->itemToString($this->func->getItem(0));
        $result .= ' AND ';
        $result .= $this->itemToString($this->func->getItem(1));
        $result .= ' ';

        return $result;
    }
}
