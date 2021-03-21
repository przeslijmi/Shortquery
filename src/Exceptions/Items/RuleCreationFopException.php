<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Rule Factory failed its operation - given settings for Rule were inproper. See causes.
 */
class RuleCreationFopException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Rule Factory failed its operation - given settings for Rule were inproper. See causes.';
}
