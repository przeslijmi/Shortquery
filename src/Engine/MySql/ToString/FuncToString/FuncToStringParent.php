<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;

use Przeslijmi\Shortquery\Engine\MySql\ToString\FalseValToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\FieldToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\IntValToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\NullValToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\TrueValToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\ValsToString;
use Przeslijmi\Shortquery\Engine\MySql\ToString\ValToString;
use Przeslijmi\Shortquery\Exceptions\Items\FuncToStringInappropriateCompMethodException;
use Przeslijmi\Shortquery\Exceptions\Items\FuncToStringItemsNotEqualException;
use Przeslijmi\Shortquery\Exceptions\Items\FuncToStringItemsToFewException;
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
     * Context name - where are you going to use result of this `FieldToString` class?
     *
     * @var string
     */
    private $context;

    /**
     * Constructor.
     *
     * @param Func   $func    Func to be converted to string.
     * @param string $context Name of context.
     */
    public function __construct(Func $func, string $context)
    {

        $this->func    = $func;
        $this->context = $context;
    }

    /**
     * Sniff to check if func parameter count is wrong.
     *
     * @param integer $properCount Proper (and only proper) number of parameters for given func.
     *
     * @throws FuncToStringItemsNotEqualException When func parameter count is not equal to desired one.
     * @return void
     */
    protected function throwIfItemsCountNotEquals(int $properCount) : void
    {

        if ($this->func->countItems() !== $properCount) {
            throw new FuncToStringItemsNotEqualException([
                $this->func->getName(),
                (string) $properCount,
                (string) $this->func->countItems(),
            ]);
        }
    }

    /**
     * Sniff to check if func parameter count is lower than given minimum.
     *
     * @param integer $minCount Minimum acceptable number of parameters for given func.
     *
     * @throws FuncToStringItemsToFewException When func parameter count is not equal to desired one.
     * @return void
     */
    protected function throwIfItemsCountLessThan(int $minCount) : void
    {

        if ($this->func->countItems() < $minCount) {
            throw new FuncToStringItemsToFewException([
                $this->func->getName(),
                (string) $minCount,
                (string) $this->func->countItems(),
            ]);
        }
    }

    /**
     * Sniff to check if func can be used with ordered comparison method.
     *
     * @todo Line 8 of this method's body. Shortcut - parent of this func is rule ... ???
     *
     * @throws FuncToStringInappropriateCompMethodException When rule comp method is not proper for this func.
     * @return void
     */
    protected function throwIfCompMethodIsInappropriate() : void
    {

        // Shortcut - parent of this func is rule ... ???.
        if ($this->func->isRuleAParent() === false) {
            return;
        }

        // Get comparison method name (string).
        $compMethod = $this->func->getRuleParent()->getComp()->getMethod();

        // Throw.
        if (in_array($compMethod, $this->onlyForCompMethods) === false) {
            throw new FuncToStringInappropriateCompMethodException([
                $this->func->getName(),
                implode(', ', $this->onlyForCompMethods),
                $compMethod,
            ]);
        }
    }

    /**
     * Convert one ContentItem (func parameter) to string.
     *
     * @param ContentItem $item ContentItem to be converted.
     *
     * @return string
     *
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity
     */
    protected function itemToString(ContentItem $item) : string
    {

        // Lvd.
        $isA    = get_class($item);
        $result = '';

        switch ($isA) {
            case 'Przeslijmi\Shortquery\Items\Val':
                $result = ( new ValToString($item, $this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\IntVal':
                $result = ( new IntValToString($item, $this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\NullVal':
                $result = ( new NullValToString($this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Vals':
                $result = ( new ValsToString($item, $this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Func':
                $result = ( new FuncToString($item, $this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Field':
                $result = ( new FieldToString($item, $this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\TrueVal':
                $result = ( new TrueValToString($this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\FalseVal':
                $result = ( new FalseValToString($this->context) )->toString();
            break;
        }//end switch

        return $result;
    }

    /**
     * Make corresponding (preceding) Comp element silent (ie. empty string).
     *
     * @return void
     */
    protected function makeCompSilent() : void
    {

        if ($this->func->isRuleAParent() === true) {
            $this->func->getRuleParent()->getComp()->setSilent();
        }
    }
}
