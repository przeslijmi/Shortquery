<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Model has given database name but its engine is not existing.
 * All engines has to be defined in `PRZESLIJMI_SHORTQUERY_ENGINES`.
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class ModelEngineDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Model has given database name but its engine is not existing. All engines has to be defined in `PRZESLIJMI_SHORTQUERY_ENGINES`.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'modelClass',
    ];
}
