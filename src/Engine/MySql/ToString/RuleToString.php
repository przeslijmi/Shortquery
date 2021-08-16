<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString;

use Przeslijmi\Shortquery\Items\Rule;

/**
 * Converts Rule element into string.
 *
 * ## Usage example
 * ```
 * $rule = new Rule(new Field('name'), new Comp('eq'), new Val('john'));
 * echo (new RuleToString())->toString(); // will return `name`='john'
 * ```
 */
class RuleToString
{

    /**
     * Rule element to be converted to string.
     *
     * @var Rule
     */
    private $rule;

    /**
     * Context name - where are you going to use result of this `FieldToString` class?
     *
     * @var string
     */
    private $context;

    /**
     * Constructor.
     *
     * @param Rule   $rule    Rule element to be converted to string.
     * @param string $context Name of context.
     */
    public function __construct(Rule $rule, string $context = '')
    {

        $this->rule    = $rule;
        $this->context = $context;
    }

    /**
     * Converts to string.
     *
     * @return string
     *
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity
     */
    public function toString() : string
    {

        $left  = $this->rule->getLeft();
        $right = $this->rule->getRight();

        $leftIs  = get_class($left);
        $rightIs = get_class($right);

        $compMethodIs = $this->rule->getComp()->getMethod();

        switch ($leftIs) {
            case 'Przeslijmi\Shortquery\Items\Field':
                $left = ( new FieldToString($left, $this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Val':
                $left = ( new ValToString($left, $this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\IntVal':
                $left = ( new IntValToString($left, $this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\NullVal':
                $left = ( new NullValToString($this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Vals':
                $left = ( new ValsToString($left, $this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Func':
                $left = ( new FuncToString($left, $this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\TrueVal':
                $left = ( new TrueValToString($this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\FalseVal':
                $left = ( new FalseValToString($this->context) )->toString();
            break;
        }//end switch

        switch ($rightIs) {
            case 'Przeslijmi\Shortquery\Items\Field':
                $right = ( new FieldToString($right, $this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Val':
                $right = ( new ValToString($right, $this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\IntVal':
                $right = ( new IntValToString($right, $this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\NullVal':
                $right = ( new NullValToString($this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Vals':
                $right = ( new ValsToString($right, $this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Func':
                $right = ( new FuncToString($right, $this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\TrueVal':
                $right = ( new TrueValToString($this->context) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\FalseVal':
                $right = ( new FalseValToString($this->context) )->toString();
            break;
        }//end switch

        if ($rightIs === 'Przeslijmi\Shortquery\Items\NullVal' && $compMethodIs === 'eq') {
            $this->rule->getComp()->setMethod('is');
        } elseif ($rightIs === 'Przeslijmi\Shortquery\Items\NullVal' && $compMethodIs === 'neq') {
            $this->rule->getComp()->setMethod('nis');
        }

        $comp = ( new CompToString($this->rule->getComp()) )->toString();

        return $left . $comp . $right;
    }
}
