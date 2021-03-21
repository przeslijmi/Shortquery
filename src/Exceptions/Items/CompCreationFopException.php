<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Comp Factory failed its operation - given settings for Comp were inproper. See causes.
 */
class CompCreationFopException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Comp Factory failed its operation - given settings for Comp were inproper. See causes.';
}
