<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Data;

use Przeslijmi\Sexceptions\Sexception;

/**
 * InstancesFactory can take only collection name or collection instance as argument.
 */
class InstanceCreationWrongParamException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'InstancesFactory can take only collection name or collection instance as argument.';
}
