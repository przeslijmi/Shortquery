<?php

namespace Przeslijmi\Shortquery\Items;

class Val extends ContentItem
{

    private $value;

    public function __construct(string $value)
    {

        $this->value = $value;
    }

    public function getValue() : string
    {

        return $this->value;
    }
}
