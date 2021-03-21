<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Logic Factory failed its operation - given settings for Logic were inproper. See causes.
 */
class LogicCreationFopException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Logic Factory failed its operation - given settings for Logic were inproper. See causes.';
}
