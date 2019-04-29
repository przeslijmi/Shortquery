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
     * @since  v1.0
     * @throws PropertyIsEmptyException On name.
     * @return string
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
     * @since  v1.0
     * @return void
     */
    public function setName(string $name) : void
    {

        $this->name = $name;
    }

    /**
     * Getter for engine name.
     *
     * @since  v1.0
     * @throws PropertyIsEmptyException On engine.
     * @return string
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
     * @since  v1.0
     * @return void
     */
    public function setEngine(string $engine) : void
    {

        $this->engine = $engine;
    }

    /**
     * Getter for instance class name.
     *
     * @since  v1.0
     * @throws PropertyIsEmptyException On instanceName.
     * @return string
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
     * @since  v1.0
     * @throws ClassDonoexException On collectionInstanceClass.
     * @throws MethodFopException On settingInstanceName.
     * @return void
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
     * @since  v1.0
     * @return array Field[].
     */
    public function getFields() : array
    {

        return $this->fields;
    }

    /**
     * Return Field by given name.
     *
     * @param string $name Name of field.
     *
     * @since  v1.0
     * @throws KeyDonoexException On fieldDoesNotExists.
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

            array_walk(
                $fieldNameExploded,
                function (&$value) {
                    $value = ucfirst($value);
                }
            );

            $gettersNames[] = 'get' . implode('', $fieldNameExploded);
        }

        return $gettersNames;
    }

    /**
     * Return list of setter methods of each field.
     *
     * @since  v1.0
     * @return array string[].
     */
    public function getFieldsSettersNames() : array
    {

        $gettersNames = [];

        foreach ($this->getFieldsNames() as $fieldName) {

            $fieldNameExploded = explode('_', $fieldName);

            array_walk(
                $fieldNameExploded,
                function (&$value) {
                    $value = ucfirst($value);
                }
            );

            $gettersNames[] = 'set' . implode('', $propNameExploded);
        }

        return $gettersNames;
    }

    /**
     * Adds field for the model.
     *
     * @param Field $field Field object.
     *
     * @since  v1.0
     * @throws KeyAlrexException On fieldWithThisNameAlreadyExists.
     * @return void
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
     * @since  v1.0
     * @return array Relation[].
     */
    public function getRelations() : array
    {

        return $this->relations;
    }

    /**
     * Return Relation by given name.
     *
     * @param string $name Name of relation.
     *
     * @since  v1.0
     * @throws KeyDonoexException On relationDoesNotExists.
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
     * @since  v1.0
     * @return array string[].
     */
    public function getRelationsNames() : array
    {

        return array_keys($this->relations);
    }

    /**
     * Adds relation for the model.
     *
     * @param Relation $relation Relation object.
     *
     * @since  v1.0
     * @throws KeyAlrexException On relationWithThisNameAlreadyExists.
     * @return void
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
