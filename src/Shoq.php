<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

/**
 * Class for constance for whole package.
 */
class Shoq
{

    /**
     * Existing comparison methods.
     *
     * @var array
     *
     * @phpcs:disable Squiz.Commenting.PostStatementComment
     */
    const COMPARISON_METHODS = [
        'eq',  // Equal.
        'neq', // Not equal.
        'leq', // Lower or equal.
        'is',  // IS.
        'nis', // NOT IS.
    ];
}
