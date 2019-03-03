<?php

namespace Przeslijmi\Shortquery\Items;

use Przeslijmi\Shortquery\Items\Comp;
use Przeslijmi\Shortquery\Items\Field;
use Przeslijmi\Shortquery\Items\Func;
use Przeslijmi\Shortquery\Items\Val;
use Przeslijmi\Shortquery\Items\Vals;

/**
 * Used in queries to change standard understanding of command sequence.
 *
 * ## Usage example
 * ```
 * use Przeslijmi\Shortquery\Items\ItemsFactory as Sifc;
 * \\ ...
 * $collection = new SomeKindOfCollection();
 * $collection
 *     ->addRuleEq('id', 3)                   // normal behaviour - first is Field, second is Val
 *     ->addRuleEq('id', Sifc::field('name')) // changed behaviour (id has to be equal to name) - second is also Field
 *     ;
 * ```
 */
class ItemsFactory
{

    /**
     * Creates Comp object.
     *
     * @return Comp
     */
    public static function comp() : Comp
    {

        return Comp::make(...func_get_args());
    }

    /**
     * Creates Field object.
     *
     * @return Field
     */
    public static function field() : Field
    {

        return new Field(...func_get_args());
    }

    /**
     * Creates Func object.
     *
     * @return Func
     */
    public static function func() : Func
    {

        return Func::make(...func_get_args());
    }

    /**
     * Creates Val object.
     *
     * @return Val
     */
    public static function val() : Val
    {

        return Val::make(...func_get_args());
    }

    /**
     * Creates Vals object.
     *
     * @return Vals
     */
    public static function vals() : Val
    {

        return Vals::make(...func_get_args());
    }
}
