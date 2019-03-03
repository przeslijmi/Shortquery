<?php

namespace Przeslijmi\Shortquery\Data;

class Field
{

    private $name; // string
    private $type; // string
    private $isPrimaryKey = false; // bool

    public function __construct(string $name, string $type)
    {

        $this->name = $name;
        $this->type = $type;
    }

    public function setPrimaryKey(bool $isPrimaryKey=true) : self
    {

        $this->isPrimaryKey = $isPrimaryKey;

        return $this;
    }

    public function isPrimaryKey() : bool
    {

        return $this->isPrimaryKey;
    }

    public function getName() : string
    {

        return $this->name;
    }

    public function getGetterName() : string
    {

        $nameExploded = explode('_', $this->name);

        array_walk($nameExploded, function(&$value, $key) {
            $value = ucfirst($value);
        });

        return 'get' . implode('', $nameExploded);
    }

    public function getSetterName() : string
    {

        $nameExploded = explode('_', $this->name);

        array_walk($nameExploded, function(&$value, $key) {
            $value = ucfirst($value);
        });

        return 'set' . implode('', $nameExploded);
    }
}
