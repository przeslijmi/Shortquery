<?php

namespace Przeslijmi\Shortquery\Engine\Mysql\ToString;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Sexceptions\Exceptions\ParamOtosetException;
use Przeslijmi\Sexceptions\Sexception;
use Przeslijmi\Shortquery\Items\Func;

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
     * @var   Func
     * @since v1.0
     */
    private $func;

    const SERVED_FUNCS = [
        'between' => 'Przeslijmi\Shortquery\Engine\Mysql\ToString\FuncToString\FuncBetweenToString',
        'in'      => 'Przeslijmi\Shortquery\Engine\Mysql\ToString\FuncToString\FuncInToString',
        'min'     => 'Przeslijmi\Shortquery\Engine\Mysql\ToString\FuncToString\FuncMinToString',
    ];

    /**
     * Constructor.
     *
     * @param Func $func Func element to be converted to string.
     *
     * @since v1.0
     */
    public function __construct(Func $func)
    {

        $this->func = $func;
    }

    /**
     * Converts to string.
     *
     * @since  v1.0
     * @throws ParamOtosetException On functionName when given function name is not present.
     * @throws MethodFopException When sth went wrong on converting Func toString.
     * @return string
     */
    public function toString() : string
    {

        try {

            if (isset(self::SERVED_FUNCS[$this->func->getName()]) === false) {
                throw new ParamOtosetException(
                    'functionName',
                    self::SERVED_FUNCS,
                    $this->func->getName()
                );
            }

            $childClassName = self::SERVED_FUNCS[$this->func->getName()];
            $child          = new $childClassName($this->func);
            $result         = $child->toString();

        } catch (Sexception $e) {
            throw ( new MethodFopException('toString', $e) )
                ->addInfo('funcName', $this->func->getName());
        }

        return $result;
    }
}
