<?php

namespace Przeslijmi\Shortquery\Engine\MySql\Queries;

use Przeslijmi\Shortquery\Engine\MySql;

class InsertQuery
{

    private $engine;
    private $collection;

    public function __construct(MySql $engine)
    {

        $this->engine = $engine;
        $this->collection = $engine->getCollection();

    }

    public function toString()
    {


        $setsToAdd = [];

        $fieldsGettersNames = $this->collection->getModel()->getFieldsGettersNames();

        foreach ($this->collection->getObjects() as $object) {

            $fieldsToAdd = [];

            foreach ($fieldsGettersNames as $getterName) {

                $value = $object->$getterName();

                if (is_null($value) === true) {
                    $fieldsToAdd[] = 'NULL';
                } else if (is_string($value) === true) {
                    $fieldsToAdd[] = "'" . addslashes($value) . "'";
                } else if (is_bool($value) === true) {
                    $fieldsToAdd[] = (int)$value;
                } else if (is_scalar($value) === true) {
                    $fieldsToAdd[] = str_replace(',', '.', $value);
                } else {
                    die('jdfgoijaf3498afjw9qjg54');
                }
            }

            $setsToAdd[] = '(' . implode(', ', $fieldsToAdd) . ')';
        }

        $query = 'INSERT INTO ' . $this->collection->getModel()->getName() . ' VALUES ' . implode(', ', $setsToAdd) . ';';

        var_dump($query);

        return $query;
    }
}
