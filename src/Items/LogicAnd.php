<?php

namespace Przeslijmi\Shortquery\Items;

class LogicAnd extends LogicItem
{

    public function __construct(Rule $rule)
    {

        parent::__construct(...func_get_args());
    }
}
