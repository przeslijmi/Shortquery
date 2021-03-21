<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Relation was created with call to create Rule. But Rule Factory failed its operation - given settings
 * for Rule were inproper. See causes.
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class RelationFailedToCreateRule extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Relation was created with call to create Rule. But Rule Factory failed its operation - given settings for Rule were inproper. See causes.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'relationName',
        'relationClass',
    ];
}
