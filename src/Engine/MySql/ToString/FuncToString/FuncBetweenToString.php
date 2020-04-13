<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;

/**
 * Converts function BETWEEN to string.
 */
class FuncBetweenToString extends FuncToStringParent
{

    /**
     * Func possible only for given comparison methods.
     *
     * @var string[]
     */
    protected $onlyForCompMethods = [ 'eq', 'neq' ];

    /**
     * Converts func BETWEEN to string.
     *
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

        $result  = $negation . ' BETWEEN ';
        $result .= $this->itemToString($this->func->getItem(0));
        $result .= ' AND ';
        $result .= $this->itemToString($this->func->getItem(1));
        $result .= ' ';

        return $result;
    }
}
