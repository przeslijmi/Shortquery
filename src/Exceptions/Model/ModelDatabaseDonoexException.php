<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * Model no database defined.
 */
class ModelDatabaseDonoexException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param Model $model Model that has the problem.
     */
    public function __construct(Model $model)
    {

        // Lvd.
        $hint  = 'Model has no given database name, use eg. `$model->setDatabase(\'mySql\')`';
        $hint .= ' to fix. Database has to be defined in `PRZESLIJMI_SHORTQUERY_DATABASES`.';

        // Define.
        $this->addInfo('context', 'DefiningModel');
        $this->addInfo('modelClass', get_class($model));
        $this->addHint($hint);
    }
}
