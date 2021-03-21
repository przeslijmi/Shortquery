<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Model has no given collection class name, use `$model->setCollectionClassName($nonEmptyCollectionClassName)` to fix.
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class ModelCollectionClassNameDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Model has no given collection class name, use `$model->setCollectionClassName($nonEmptyCollectionClassName)` to fix.';

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
