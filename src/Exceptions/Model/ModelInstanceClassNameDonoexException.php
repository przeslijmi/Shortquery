<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Model has no given instance class name, use `$model->setInstanceClassName($nonEmptyInstanceClassName)` to fix.
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class ModelInstanceClassNameDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Model has no given instance class name, use `$model->setInstanceClassName($nonEmptyInstanceClassName)` to fix.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'modelName',
        'modelClass',
    ];
}
