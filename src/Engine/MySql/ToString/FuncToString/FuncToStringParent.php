<?php

namespace Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Sexceptions\Exceptions\ParamOtosetException;
use Przeslijmi\Shortquery\Items\ContentItem;
use Przeslijmi\Shortquery\Items\Func;
use Przeslijmi\Shortquery\Engine\MySql\ToString\ValToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\ValsToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\FieldToString;

class FuncToStringParent
{

    protected $func; // Func
    protected $onlyForCompMethods = [];

    public function __construct(Func $func)
    {

        $this->func = $func;
    }

    protected function throwIfItemsCountNotEquals(int $properCount) : void
    {

        if ($this->func->countItems() !== $properCount) {
            throw (new MethodFopException('convertingFuncToString'))
                ->addInfo('itemsNeeded', $properCount)
                ->addInfo('itemsGiven', $this->func->countItems());
        }
    }

    protected function throwIfItemsCountLessThan(int $minCount) : void
    {

        if ($this->func->countItems() < $minCount) {
            throw (new MethodFopException('convertingFuncToString'))
                ->addInfo('itemsNeededAtLeas', $minCount)
                ->addInfo('itemsGiven', $this->func->countItems());
        }
    }

    protected function throwIfCompMethodIsInappropriate() : void
    {

        if (count($this->onlyForCompMethods) === 0) {
            return;
        }

        if ($this->func->isRuleAParent() === false) {
            return;
        }

        $compMethod = $this->func->getRuleParent()->getComp()->getMethod();

        try {
            if (in_array($compMethod, $this->onlyForCompMethods) === false) {
                throw new ParamOtosetException('ruleCompMethod', $this->onlyForCompMethods, $compMethod);
            }
        } catch (ParamOtosetException $e) {
            throw (new MethodFopException('convertingFuncToString', $e))
                ->addInfo('funcUsedWithInappropriateCompMethod', $compMethod);
        }
    }

    protected function itemToString(ContentItem $item) : string
    {

        $isA = get_class($item);
        $result = '';

        switch ($isA) {
        case 'Przeslijmi\Shortquery\Items\Val':
            $result = (new ValToString($item))->toString();
            break;
        case 'Przeslijmi\Shortquery\Items\Vals':
            $result = (new ValsToString($item))->toString();
            break;
        case 'Przeslijmi\Shortquery\Items\Func':
            $result = (new FuncToString($item))->toString();
            break;
        case 'Przeslijmi\Shortquery\Items\Field':
            $result = (new FieldToString($item))->toString();
            break;
        }

        return $result;
    }

    /**
     * Make corresponding (preceding) Comp element silent (ie. empty string).
     *
     * @since  v1.0
     * @return void
     */
    protected function makeCompSilent() : void
    {

        if ($this->func->isRuleAParent() === true) {
            $this->func->getRuleParent()->getComp()->setSilent();
        }
    }
}