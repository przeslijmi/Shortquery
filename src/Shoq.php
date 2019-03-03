<?php

namespace Przeslijmi\Shortquery;

class Shoq
{

    private $raw;
    const COMPARISON_METHODS = [
        'eq',  // equal
        'neq', // not equal
        'leq', // lower or equal
    ];

    public function __construct(string $raw)
    {

        $this->raw = $raw;
        $this->parse();
    }

    private function parse() : void
    {


    // $split = new Splitter($shoq, '#', [ '.', '[' ]);

    // $split = new Splitter($shoq, '.', [ '#', '[' ]);

    // $split = new Splitter($shoq, '[', [ '.', '#', ']' ]);
    }
}