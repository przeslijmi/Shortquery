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
        'gt',  // Greater than.
        'geq', // Greater or equal.
        'leq', // Less or equal.
        'lt',  // Less then.
        'is',  // Is.
        'nis', // Not is.
        'lk',  // Like.
        'nlk', // Not like.
    ];
}
