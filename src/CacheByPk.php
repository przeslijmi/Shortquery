<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Tools\InstancesFactory;

class CacheByPk
{

    private $data = [];
    private $usedPks = [];

    public function __construct(string $model)
    {

        $this->model = new $model();
        $this->select = $this->model->newSelect();
    }

    public function getSelect()
    {

        return $this->select;
    }

    public function prepare()
    {

        $pkFieldName = $this->model->getPkField()->getName();
        $this->data = $this->select->readBy($pkFieldName);
    }

    public function get(string $pkValue)
    {

        $instance = $this->model->getNewInstance();
        $data = ( $this->data[$pkValue] ?? null );

        if ($data !== null) {

            $instance = InstancesFactory::fromArray($instance, $data);
            $instance->defineIsAdded(true);
            $instance->defineNothingChanged();

            return $instance;
        }

        $pkSetter = $this->model->getPkField()->getSetterName();

        $instance->$pkSetter($pkValue);

        return $instance;
    }

    public function getOnce(string $pkValue)
    {

        if (in_array($pkValue, $this->usedPks)) {
            die('only once (' . $pkValue . ')!');
        }

        $this->usedPks[] = $pkValue;

        return $this->get($pkValue);
    }

    public function takeOutOnce(string $pkValue) : void
    {

        // The same as get one but without actually getting - saves time when get is not needed.

        if (in_array($pkValue, $this->usedPks)) {
            die('only once (' . $pkValue . ')!');
        }

        $this->usedPks[] = $pkValue;
    }

    public function getNonUsedPks() : array
    {

        return array_diff(array_keys($this->data), $this->usedPks);
    }
}
