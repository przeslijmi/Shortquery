<?php

namespace Przeslijmi\Shortquery\Data;

use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Sexceptions\Sexception;
use Przeslijmi\Shortquery\Data\Collection\Model;
use Przeslijmi\Shortquery\Items\LogicAnd;
use Przeslijmi\Shortquery\Items\LogicOr;
use Przeslijmi\Shortquery\Items\Rule;
use Przeslijmi\Shortquery\Shoq;
use Przeslijmi\Shortquery\Tools\InstancesFactory;

/**
 * Parent for all model objects.
 */
abstract class Collection
{

    /**
     * Model which is used by this collection.
     *
     * @var Model
     */
    protected $model;

    /**
     * Group B - Array of logics defined for this model.
     *
     * @var   LogicItem[]
     * @since v1.0
     */
    public $logics = [];

    /**
     * Group B - Array of objects (potential records?).
     *
     * @var object[]
     */
    private $objects = [];

    /**
     * Group A - Constructor.
     *
     * @since v1.0
     */
    public function __construct()
    {

        $this->model = new Model($this);
    }

    /**
     * Group B - Getter for model.
     *
     * @since  v1.0
     * @return Model
     */
    public function getModel() : Model
    {

        return $this->model;
    }

    /**
     * Group B - Getter for array of all logics.
     *
     * @since  v1.0
     * @return LogicItem[]
     */
    public function getLogics() : array
    {

        return $this->logics;
    }

    /**
     * Group B - Setter for adding new rule (and therefore also LogicAnd) to the model.
     *
     * @since  v1.0
     * @return self
     * @throws MethodFopException When creation of Rule have failed.
     */
    public function addRule() : self
    {

        try {
            $rule = Rule::make(...func_get_args());
        } catch (Sexception $e) {
            throw ( new MethodFopException('creationOfRuleFailed', $e) )
                ->addInfos(func_get_args(), 'ruleArgs');
        }

        $logic = new LogicAnd($rule);
        $logic->setCollectionParent($this);

        $this->logics[] = $logic;

        return $this;
    }

    /**
     * Group B - Setter for adding new rule "equals" (and therefore also LogicAnd) to the model.
     *
     * @since  v1.0
     * @return self
     * @throws MethodFopException When creation of Rule have failed.
     */
    public function addRuleEq() : self
    {

        try {
            $ruleArgs = [ func_get_arg(0), 'eq', func_get_arg(1) ];
            $rule     = Rule::make(...$ruleArgs);
        } catch (Sexception $e) {
            throw ( new MethodFopException('creationOfRuleFailed', $e) )
                ->addInfos($ruleArgs, 'ruleArgs');
        }

        $logic = new LogicAnd($rule);
        $logic->setCollectionParent($this);

        $this->logics[] = $logic;

        return $this;
    }

    /**
     * Group B - Setter for adding new rule "not equals" (and therefore also LogicAnd) to the model.
     *
     * @since  v1.0
     * @return self
     * @throws MethodFopException When creation of Rule have failed.
     */
    public function addRuleNeq() : self
    {

        try {
            $ruleArgs = [ func_get_arg(0), 'neq', func_get_arg(1) ];
            $rule     = Rule::make(...$ruleArgs);
        } catch (Sexception $e) {
            throw ( new MethodFopException('creationOfRuleFailed', $e) )
                ->addInfos($ruleArgs, 'ruleArgs');
        }

        $logic = new LogicAnd($rule);
        $logic->setCollectionParent($this);

        $this->logics[] = $logic;

        return $this;
    }

    /**
     * Group B - Setter for adding new rule LogicOr to the model.
     *
     * @param array ...$rulesDefinitions Array of arrays with rules definitions.
     *
     * @since  v1.0
     * @return self
     */
    public function addLogicOr(array ...$rulesDefinitions) : self
    {

        foreach ($rulesDefinitions as $ruleDefinition) {
            $rules[] = Rule::make(...$ruleDefinition);
        }

        $logic = new LogicOr(...$rules);
        $logic->setCollectionParent($this);

        $this->logics[] = $logic;

        return $this;
    }

    /**
     * Group C - Getter for objects.
     *
     * @since  v1.0
     * @return object[]
     */
    public function getObjects() : array
    {

        return $this->objects;
    }

    /**
     * Group C - Getter for one object (by order number of object).
     *
     * @param integer $number Order number of the object.
     *
     * @since  v1.0
     * @return object
     */
    public function getObject(int $number = 0) : object
    {

        return $this->objects[$number];
    }

    /**
     * Group C - Getter for one object (by id of object).
     *
     * @param integer $id Id of the object.
     *
     * @todo   replace die with throw
     * @since  v1.0
     * @return object
     */
    public function getObjectWithId(int $id) : object
    {

        foreach ($this->getObjects() as $object) {
            if ($object->getId() === $id) {
                return $object;
            }
        }

        die('sdfgdasdfjer492894q5wg4');
    }

    /**
     * Group C - Getter of one value key for all objects.
     *
     * @param string $getterMethodName What is a getter name for each object to get this value.
     *
     * @since  v1.0
     * @return array
     */
    public function getValueForObjects(string $getterMethodName) : array
    {

        $result = [];

        foreach ($this->getObjects() as $object) {
            $result[] = $object->$getterMethodName();
        }

        return $result;
    }

    /**
     * Group C - Add one object to collection.
     *
     * @param object $object Object to be put.
     *
     * @since  v1.0
     * @return void
     */
    public function put(object $object) : void
    {

        $this->objects[] = $object;
    }

    /**
     * Group C - Add one or more ojects to collection.
     *
     * @param array $objects Array of object that is needed.
     *
     * @since  v1.0
     * @return void
     */
    public function putMany(array $objects) : void
    {

        foreach ($objects as $object) {
            $this->put($object);
        }
    }

    /**
     * Group C - Not sure what it does.
     *
     * @param Collection $newCollection Todo what?
     * @param Relation   $relation      Todo what?
     *
     * @since  v1.0
     * @return void
     */
    public function unpack(Collection $newCollection, Relation $relation) : void
    {

        $fieldFromGetter = $relation->getFieldFrom()->getGetterName();
        $fieldToGetter   = $relation->getFieldTo()->getGetterName();
        $adderMethodName = $relation->getAdderMethodName();

        // var_dump('$fieldFromGetter', $fieldFromGetter);
        // var_dump('$fieldToGetter', $fieldToGetter);
        foreach ($newCollection->getObjects() as $newObject) {

            $keyTo = $newObject->$fieldToGetter();

            // var_dump('$keyTo', $keyTo);
            // @todo - very slow
            foreach ($this->getObjects() as $oldObject) {
                if ($oldObject->$fieldFromGetter() === $keyTo) {
                    $oldObject->$adderMethodName($newObject);
                    // var_dump('dodaje');
                }
            }

            // $this->getObjectWithId($id)->$addingMethodName($object);
        }
    }


    /**
     * Group D - Gets all records from DB.
     *
     * @since  v1.0
     * @return array Array of plain records from db.
     */
    public function readRecords() : array
    {

        $engine = new \Przeslijmi\Shortquery\Engine\MySql();
        $engine->setCollection($this);
        $engine->addLogics(...$this->logics);
        $records = $engine->read();

        return $records;
    }

    /**
     * Group D - Gets one record from DB.
     *
     * @since  v1.0
     * @return array Array of plain records from db.
     */
    public function readOneRecord() : array
    {

        $records = $this->readRecords();

        return array_slice($records, 0, 1)[0];
    }

    /**
     * Group D - Calls engine to get/select/read data.
     *
     * @since  v1.0
     * @return array Array of Instances (ef. Car[]) with records.
     */
    public function read() : array
    {

        $records = $this->readRecords();

        foreach ($records as $key => $record) {
            $this->objects[] = InstancesFactory::fromArray($this->model->getInstanceName(), $record);
        }

        return $this->getObjects();
    }

    /**
     * Group D - Calls engine to get/select/read data.
     *
     * @since  v1.0
     * @return array Array of Instances (ef. Car[]) with records.
     */
    public function readOne() : object
    {

        $record = $this->readOneRecord();
        $object = InstancesFactory::fromArray($this->model->getInstanceName(), $record);

        $this->objects[] = $object;

        return $object;
    }

    /**
     * Group D - Calls engine to insert data.
     *
     * @since  v1.0
     * @return void
     */
    public function create() : void
    {

        $engine = new \Przeslijmi\Shortquery\Engine\MySql();
        $engine->setCollection($this);
        $records = $engine->create();
    }
}
