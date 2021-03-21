<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Model has no given database name, use eg. `$model->setDatabase(\'mySql\')` to fix.
 *
 * Database has to be defined in `PRZESLIJMI_SHORTQUERY_DATABASES`.
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class ModelDatabaseDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Model has no given database name, use eg. `$model->setDatabase(\'mySql\')` to fix. Database has to be defined in `PRZESLIJMI_SHORTQUERY_DATABASES`.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'modelClass',
    ];
}
