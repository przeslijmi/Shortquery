<?php

namespace Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;

class FuncInToString extends FuncToStringParent
{

    protected $onlyForCompMethods = [ 'eq', 'neq' ];

    /**
     * Converts func IN to string.
     *
     * @since  v1.0
     * @return string
     */
    public function toString() : string
    {

        $negation = '';

        $this->makeCompSilent();
        $this->throwIfItemsCountLessThan(1);
        $this->throwIfCompMethodIsInappropriate();

        if ($this->func->isRuleAParent()) {
            if ($this->func->getRuleParent()->getComp()->getMethod() === 'neq') {
                $negation = ' NOT';
            }
        }

        $results = [];

        foreach ($this->func->getItems() as $item) {
            $results[] = $this->itemToString($item);
        }

        $result = $negation . ' IN (' . implode(', ', $results) . ') ';

        return $result;
    }
}
