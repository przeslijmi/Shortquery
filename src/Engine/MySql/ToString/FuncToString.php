<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\ToString;

use Przeslijmi\Shortquery\Exceptions\Items\FuncNameOtosetException;
use Przeslijmi\Shortquery\Exceptions\Items\FuncToStringFopException;
use Przeslijmi\Shortquery\Items\Func;
use Throwable;

/**
 * Converts Func element into string.
 *
 * ## Usage example
 * ```
 * $func = new Func('between', [ 25, 30 ]);
 * echo (new FuncToString($func))->toString();
 * // will return
 * // BETWEEN '25' AND '30'
 * // and make Comp element silent
 * ```
 *
 * ## Silencing Comp element
 * Sometimes Func have to make Comp element silent. For eg function BETWEEN. The syntax:
 * ```
 * WHERE `age` = BETWEEN 25 AND 30
 * ```
 * is wrong. Comp equals sign has to be silenced. To do so just call:
 * ```
 * $this->makeCompSilent();
 * ```
 * inside `func*ToString()` method.
 */
class FuncToString
{

    /**
     * Collection of Func elements to be converted to string.
     *
     * @var Func
     */
    private $func;

    /**
     * Context name - where are you going to use result of this `FieldToString` class?
     *
     * @var string
     */
    private $context;

    const SERVED_FUNCS = [
        'between'      => 'Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString\FuncBetweenToString',
        'concat'       => 'Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString\FuncConcatToString',
        'count'        => 'Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString\FuncCountToString',
        'localfunc'    => 'Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString\FuncLocalToString',
        'datediffdays' => 'Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString\FuncDateDiffDaysToString',
        'in'           => 'Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString\FuncInToString',
        'inset'        => 'Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString\FuncInSetToString',
        'min'          => 'Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString\FuncMinToString',
        'sum'          => 'Przeslijmi\Shortquery\Engine\MySql\ToString\FuncToString\FuncSumToString',
    ];

    /**
     * Constructor.
     *
     * @param Func   $func    Func element to be converted to string.
     * @param string $context Name of context.
     */
    public function __construct(Func $func, string $context = '')
    {

        $this->func    = $func;
        $this->context = $context;
    }

    /**
     * Converts to string.
     *
     * @throws FuncNameOtosetException When given function name is not present.
     * @throws FuncToStringFopException When sth went wrong on converting Func toString.
     * @return string
     */
    public function toString() : string
    {

        try {

            if (isset(self::SERVED_FUNCS[$this->func->getName()]) === false) {
                throw new FuncNameOtosetException([
                    implode(', ', self::SERVED_FUNCS),
                    $this->func->getName(),
                ]);
            }

            $childClassName = self::SERVED_FUNCS[$this->func->getName()];
            $child          = new $childClassName($this->func, $this->context);
            $result         = $child->toString();

            if (empty($this->func->getAlias()) === false && in_array($this->context, [ 'group', 'order' ]) === false) {
                $result .= ' AS `' . $this->func->getAlias() . '`';
            }

        } catch (Throwable $thr) {
            throw new FuncToStringFopException([ $this->func->getName() ], 0, $thr);
        }//end try

        return $result;
    }
}
