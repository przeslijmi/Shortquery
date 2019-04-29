<?php

namespace Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Sexceptions\Exceptions\ParamOtosetException;
use Przeslijmi\Shortquery\Engine\MySql\ToString\FieldToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\ValsToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\ValToString;
use Przeslijmi\Shortquery\Items\ContentItem;
use Przeslijmi\Shortquery\Items\Func;

/**
 * Parent object of every Func*ToString class.
 */
class FuncToStringParent
{

    /**
     * Func object to be converted to string.
     *
     * @var Func
     */
    protected $func;

    /**
     * Set of acceptable method names for this Func.
     *
     * @var string[]
     */
    protected $onlyForCompMethods = [];

    /**
     * Constructor.
     *
     * @param Func $func Func to be converted to string.
     *
     * @since v1.0
     */
    public function __construct(Func $func)
    {

        $this->func = $func;
    }

    /**
     * Sniff to check if func parameter count is wrong.
     *
     * @param integer $properCount Proper (and only proper) number of parameters for given func.
     *
     * @since  v1.0
     * @throws MethodFopException On convertingFuncToString.
     * @return void
     */
    protected function throwIfItemsCountNotEquals(int $properCount) : void
    {

        if ($this->func->countItems() !== $properCount) {
            throw (new MethodFopException('convertingFuncToString'))
                ->addInfo('itemsNeeded', $properCount)
                ->addInfo('itemsGiven', $this->func->countItems());
        }
    }

    /**
     * Sniff to check if func parameter count is lower than given minimum.
     *
     * @param integer $minCount Minimum acceptable number of parameters for given func.
     *
     * @since  v1.0
     * @throws MethodFopException On convertingFuncToString.
     * @return void
     */
    protected function throwIfItemsCountLessThan(int $minCount) : void
    {

        if ($this->func->countItems() < $minCount) {
            throw (new MethodFopException('convertingFuncToString'))
                ->addInfo('itemsNeededAtLeas', $minCount)
                ->addInfo('itemsGiven', $this->func->countItems());
        }
    }

    /**
     * Sniff to check if func can be used with ordered comparison method.
     *
     * @since  v1.0
     * @throws ParamOtosetException When rule comp method is not proper for this func.
     * @throws MethodFopException Rethrown from above.
     * @return void
     */
    protected function throwIfCompMethodIsInappropriate() : void
    {

        // Shortcut - there are no limitation to comp methods for this func.
        if (count($this->onlyForCompMethods) === 0) {
            return;
        }

        // Shortcut - parent of this func is rule ... ??? (@TODO).
        if ($this->func->isRuleAParent() === false) {
            return;
        }

        // Get comparison method name (string).
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

    /**
     * Convert one ContentItem (func parameter) to string.
     *
     * @param ContentItem $item ContentItem to be converted.
     *
     * @since  v1.0
     * @return string
     */
    protected function itemToString(ContentItem $item) : string
    {

        // Lvd.
        $isA    = get_class($item);
        $result = '';

        switch ($isA) {
            case 'Przeslijmi\Shortquery\Items\Val':
                $result = ( new ValToString($item) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Vals':
                $result = ( new ValsToString($item) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Func':
                $result = ( new FuncToString($item) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Field':
                $result = ( new FieldToString($item) )->toString();
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
