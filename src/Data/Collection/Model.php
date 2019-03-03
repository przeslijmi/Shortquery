<?php

namespace Przeslijmi\Shortquery\Data\Collection;

use Przeslijmi\Sexceptions\Exceptions\ClassDonoexException;
use Przeslijmi\Sexceptions\Exceptions\KeyAlrexException;
use Przeslijmi\Sexceptions\Exceptions\KeyDonoexException;
use Przeslijmi\Sexceptions\Exceptions\ParamOtosetException;
use Przeslijmi\Sexceptions\Exceptions\PropertyIsEmptyException;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Data\Relation;

/**
 * Child class of Collection containing model related information.
 *
 * @since v1.0
 */
class Model
{

    /**
     * Name of the model (eg. Vendor\Applcation\Cars).
     *
     * @var   string
     * @since v1.0
     */
    private $name = '';

    /**
     * Name of the engine of the model (eg. MySql).
     *
     * @var   string
     * @since v1.0
     */
    private $engine = '';

    /**
     * Name of the class that instances records (eg. Vendor\Applcation\Car).
     *
     * @var   string
     * @since v1.0
     */
    private $instanceName = '';

    /**
     * Collection of fields.
     *
     * @var   Field[]
     * @since v1.0
     */
    private $fields = [];

    /**
     * Collection of relations.
     *
     * @var   Relation[]
     * @since v1.0
     */
    private $relations = [];

    /**
     * Parrent Collection object.
     *
     * @var   Collection
     * @since v1.0
     */
    private $collection;

    /**
     * Constructor.
     *
     * @param Collection $collection Parent Collection object.
     *
     * @since v1.0
     */
    public function __construct(Collection $collection)
    {

        $this->collection = $collection;
    }

    /**
     * Getter for name.
     *
     * @return string
     * @since  v1.0
     */
    public function getName() : string
    {

        if (empty($this->name) === true) {
            throw new PropertyIsEmptyException('name', $cause);
        }

        return $this->name;
    }

    /**
     * Setter for name.
     *
     * @param string $name Collection name.
     *
     * @return void
     * @since  v1.0
     */
    public function setName(string $name) : void
    {

        $this->name = $name;
    }

    /**
     * Getter for engine name.
     *
     * @return string
     * @since  v1.0
     */
    public function getEngine() : string
    {

        if (empty($this->engine) === true) {
            throw new PropertyIsEmptyException('engine', $cause);
        }

        return $this->engine;
    }

    /**
     * Setter for engine name.
     *
     * @param string $engine Engine name.
     *
     * @return void
     * @since  v1.0
     */
    public function setEngine(string $engine) : void
    {

        $this->engine = $engine;
    }

    /**
     * Getter for instance class name.
     *
     * @return string
     * @since  v1.0
     */
    public function getInstanceName() : string
    {

        if (empty($this->instanceName) === true) {
            throw new PropertyIsEmptyException('instanceName', $cause);
        }

        return $this->instanceName;
    }

    /**
     * Setter for name.
     *
     * @param string $instanceName Instance class name.
     *
     * @return void
     * @since  v1.0
     */
    public function setInstanceName(string $instanceName) : void
    {

        if (class_exists($instanceName) === false) {
            try {
                throw new ClassDonoexException('collectionInstanceClass', $instanceName);
            } catch (ClassDonoexException $e) {
                throw new MethodFopException('settingInstanceName', $e);
            }
        }

        $this->instanceName = $instanceName;
    }

    /**
     * Return list of fields in model.
     *
     * @return array Field[].
     */
    public function getFields() : array
    {

        return $this->fields;
    }

    /**
     * Return Field by given name.
     *
     * @return Field.
     */
    public function getFieldByName(string $name) : Field
    {

        if (isset($this->fields[$name]) === false) {
            throw new KeyDonoexException('fieldDoesNotExists', $this->getFieldsNames(), $name);
        }

        return $this->fields[$name];
    }

    /**
     * Return list of fields names.
     *
     * @return array string[].
     */
    public function getFieldsNames() : array
    {

        return array_keys($this->fields);
    }

    /**
     * Return list of getter methods of each field.
     *
     * @return array string[].
     */
    public function getFieldsGettersNames() : array
    {

        $gettersNames = [];

        foreach ($this->getFieldsNames() as $fieldName) {

            $fieldNameExploded = explode('_', $fieldName);

            array_walk($fieldNameExploded, function(&$value, $key) {
                $value = ucfirst($value);
            });

            $gettersNames[] = 'get' . implode('', $fieldNameExploded);
        }

        return $gettersNames;
    }

    /**
     * Return list of setter methods of each field.
     *
     * @return array string[].
     */
    public function getFieldsSettersNames() : array
    {

        $gettersNames = [];

        foreach ($this->getFieldsNames() as $fieldName) {

            $fieldNameExploded = explode('_', $fieldName);

            array_walk($fieldNameExploded, function(&$value, $key) {
                $value = ucfirst($value);
            });

            $gettersNames[] = 'set' . implode('', $propNameExploded);
        }

        return $gettersNames;
    }

    /**
     * Adds field for the model.
     *
     * @param Field $field Field object.
     *
     * @return void
     * @since  v1.0
     */
    public function addField(Field $field) : void
    {

        if (isset($this->fields[$field->getName()]) === true) {
            throw new KeyAlrexException('fieldWithThisNameAlreadyExists', $field->getName());
        }

        $this->fields[$field->getName()] = $field;
    }

    /**
     * Return list of relations in model.
     *
     * @return array Relation[].
     */
    public function getRelations() : array
    {

        return $this->relations;
    }

    /**
     * Return Relation by given name.
     *
     * @return Relation.
     */
    public function getRelationByName(string $name) : Relation
    {

        if (isset($this->relations[$name]) === false) {
            throw new KeyDonoexException('relationDoesNotExists', $this->getRelationsNames(), $name);
        }

        return $this->relations[$name];
    }

    /**
     * Return list of fields names.
     *
     * @return array string[].
     */
    public function getRelationsNames() : array
    {

        return array_keys($this->relations);
    }

    /**
     * Adds field for the model.
     *
     * @param Field $field Field object.
     *
     * @return void
     * @since  v1.0
     */
    public function addRelation(Relation $relation) : void
    {

        if (isset($this->relations[$relation->getName()]) === true) {
            throw new KeyAlrexException('relationWithThisNameAlreadyExists', $relation->getName());
        }

        $relation->setModel($this);

        $this->relations[$relation->getName()] = $relation;
    }
}
