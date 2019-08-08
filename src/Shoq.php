<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

/**
 * Main ShortQuery class.
 */
class Shoq
{

    /**
     * Raw contents of the query.
     *
     * @var string
     */
    private $raw;

    /**
     * Existing comparison methods.
     *
     * @var array
     */
    const COMPARISON_METHODS = [
        'eq',  // Equal.
        'neq', // Not equal.
        'leq', // Lower or equal.
        'is',  // IS
        'nis', // NOT IS
    ];

    /**
     * Constructor.
     *
     * @param string $raw Raw contents of the query.
     *
     * @since v1.0
     */
    public function __construct(string $raw)
    {

        $this->raw = $raw;
        $this->parse();
    }

    /**
     * Parse the query.
     *
     * @since  v1.0
     * @return void
     */
    private function parse() : void
    {

        // $split = new Splitter($shoq, '#', [ '.', '[' ]);
        // $split = new Splitter($shoq, '.', [ '#', '[' ]);
        // $split = new Splitter($shoq, '[', [ '.', '#', ']' ]);
    }
}
