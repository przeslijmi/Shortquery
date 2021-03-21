<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Creator;

use Przeslijmi\Sexceptions\Sexception;

/**
 * None of possible locations for shortQuery Creator template file worked from current location.
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class TemplateFileDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'None of possible locations for shortQuery Creator template file worked from current location.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'currentLocation',
        'possibleUris',
    ];
}
