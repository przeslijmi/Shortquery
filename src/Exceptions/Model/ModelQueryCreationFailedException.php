<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\FopException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * Was trying to create SELECT, UPDATE, INSERT or DELETE query but it failed.
 */
class ModelQueryCreationFailedException extends FopException
{

    /**
     * Constructor.
     *
     * @param Model          $model Model that has the problem.
     * @param Throwable|null $cause Throwable that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(Model $model, string $queryType, ?Throwable $cause = null)
    {

        $this->addInfo('context', 'CreatingModelQuery');
        $this->addInfo('modelName', $model->getName());
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('queryType', $queryType);
        $this->addInfo('hint', 'Tryed to crete >>' . $queryType . '<< for model, but failed. See cause.');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
