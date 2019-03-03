<?php

namespace Przeslijmi\Shortquery\Items;

class Vals extends ContentItem
{

    private $value;

    public function __construct(array $value)
    {

        $this->value = $value;
    }

    public function getValues() : array
    {

        return $this->value;
    }
}
