<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;

/**
 * Converts function IN to string.
 */
class FuncInToString extends FuncToStringParent
{

    /**
     * Func possible only for given comparison methods.
     *
     * @var string[]
     */
    protected $onlyForCompMethods = [ 'eq', 'neq' ];

    /**
     * Converts func IN to string.
     *
     * @return string
     */
    public function toString() : string
    {

        $negation = '';

        $this->makeCompSilent();
        $this->throwIfItemsCountLessThan(1);
        $this->throwIfCompMethodIsInappropriate();

        if ($this->func->isRuleAParent() === true) {
            if ($this->func->getRuleParent()->getComp()->getMethod() === 'neq') {
                $negation = ' NOT';
            }
        }

        $results = [];

        foreach ($this->func->getItems() as $item) {
            $results[] = $this->itemToString($item);
        }

        $result = $negation . ' IN (' . implode(', ', $results) . ')';

        return $result;
    }
}
