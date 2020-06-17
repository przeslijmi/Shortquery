<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Creator;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Schema need to have non-empty array under key `models` defined which is missing
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class ModelsInSchemaDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Schema need to have non-empty array under key `models` defined which is missing.';
}
