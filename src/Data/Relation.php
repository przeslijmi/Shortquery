<?php

namespace Przeslijmi\Shortquery\Data;

use Przeslijmi\Shortquery\Data\Collection\Model;

class Relation
{

    private $model; // Model
    private $name; // string
    private $collectionNameFrom; // string
    private $collectionNameTo; // string
    private $fieldNameFrom; // string
    private $fieldNameTo; // string

    public function __construct(string $name)
    {

        $this->name = $name;
    }

    public function setModel(Model $model) : void
    {

        $this->model = $model;
    }

    public function setTo(string $collectionNameTo) : self
    {

        $this->collectionNameTo = $collectionNameTo;

        return $this;
    }

    public function setFieldFrom(string $fieldNameFrom) : self
    {

        $this->fieldNameFrom = $fieldNameFrom;

        return $this;
    }

    public function getFieldFrom() : Field
    {

        return $this->model->getFieldByName($this->fieldNameFrom);
    }

    public function getFieldTo() : Field
    {

        $collectionNameTo = $this->collectionNameTo;

        return (new $collectionNameTo())->getModel()->getFieldByName($this->fieldNameTo);
    }

    public function setFieldTo(string $fieldNameTo) : self
    {

        $this->fieldNameTo = $fieldNameTo;

        return $this;
    }

    public function getName() : string
    {

        return $this->name;
    }

    public function getAdderMethodName() : string
    {

        return 'add' . ucfirst($this->name);
    }
}
