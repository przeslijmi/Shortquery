<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\Mysql\ToString;

use Przeslijmi\Shortquery\Items\Rule;

/**
 * Converts Rule element into string.
 *
 * ## Usage example
 * ```
 * $rule = new Rule(new Field('name'), new Comp('eq'), new Val('john'));
 * echo (new RuleToString())->toString(); // will return `name='john'`
 * ```
 */
class RuleToString
{

    /**
     * Rule element to be converted to string.
     *
     * @var   Rule
     * @since v1.0
     */
    private $rule;

    /**
     * Constructor.
     *
     * @param Rule $rule Rule element to be converted to string.
     *
     * @since v1.0
     */
    public function __construct(Rule $rule)
    {

        $this->rule = $rule;
    }

    /**
     * Converts to string.
     *
     * @since  v1.0
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
                $left = ( new FieldToString($left) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Val':
                $left = ( new ValToString($left) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\IntVal':
                $left = ( new IntValToString($left) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\NullVal':
                $left = ( new NullValToString($left) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Vals':
                $left = ( new ValsToString($left) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Func':
                $left = ( new FuncToString($left) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\TrueVal':
                $left = ( new TrueValToString($left) )->toString();
            break;

            default:
                $left = null;
            break;
        }

        switch ($rightIs) {
            case 'Przeslijmi\Shortquery\Items\Field':
                $right = ( new FieldToString($right) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Val':
                $right = ( new ValToString($right) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\IntVal':
                $right = ( new IntValToString($right) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\NullVal':
                $right = ( new NullValToString($right) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Vals':
                $right = ( new ValsToString($right) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\Func':
                $right = ( new FuncToString($right) )->toString();
            break;

            case 'Przeslijmi\Shortquery\Items\TrueVal':
                $right = ( new TrueValToString($right) )->toString();
            break;

            default:
                $right = null;
            break;
        }

        if ($right === null || $left === null) {
            die('sfiosjioejfiosr');
        }

        if ($rightIs === 'Przeslijmi\Shortquery\Items\NullVal' && $compMethodIs === 'eq') {
            $this->rule->getComp()->setMethod('is');
        } elseif ($rightIs === 'Przeslijmi\Shortquery\Items\NullVal' && $compMethodIs === 'neq') {
            $this->rule->getComp()->setMethod('nis');
        }

        $comp = ( new CompToString($this->rule->getComp()) )->toString();

        return $left . $comp . $right;
    }
}
