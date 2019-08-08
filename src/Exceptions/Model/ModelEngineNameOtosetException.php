<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Exception;
use Przeslijmi\Sexceptions\Exceptions\ValueOtosetException;
use Przeslijmi\Shortquery\Data\Model;

/**
 * Model has wrong name.
 */
class ModelEngineNameOtosetException extends ValueOtosetException
{

    /**
     * Constructor.
     *
     * @param string         $value Given wrong engine name.
     * @param Model          $model Model that has the problem.
     * @param Exception|null $cause Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(string $value, Model $model, ?Exception $cause = null)
    {

        $this->setCodeName('ModelEngineNameOtosetException');
        $this->addInfo('name', 'modelEngineName');
        $this->addInfo('range', 'mySql');
        $this->addInfo('actualValue', $value);
        $this->addInfo('modelName', $model->getName());
        $this->addInfo('modelClass', get_class($model));
        $this->addInfo('hint', 'Model engine name `' . $value . '` is out of set (see above).');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
