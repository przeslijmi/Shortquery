<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\ValueOtosetException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * Model has wrong database name.
 */
class ModelDatabaseNameOtosetException extends ValueOtosetException
{

    /**
     * Constructor.
     *
     * @param string         $databse Given database name.
     * @param Model          $model   Model that has the problem.
     * @param Throwable|null $cause   Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $databse, Model $model, ?Throwable $cause = null)
    {

        $this->addInfo('name', 'databaseName');
        $this->addInfo('range', implode(', ', array_keys(PRZESLIJMI_SHORTQUERY_DATABASES)));
        $this->addInfo('actualValue', $databse);
        $this->addInfo('modelName', $model->getName());
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('hint', 'There is no database with name `' . $databse . '`. See possibilities above.');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
