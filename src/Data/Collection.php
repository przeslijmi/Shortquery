<?php

namespace Przeslijmi\Shortquery\Data;

use Przeslijmi\Shortquery\Shoq;
use Przeslijmi\Shortquery\Items\Rule;
use Przeslijmi\Shortquery\Items\LogicAnd;
use Przeslijmi\Shortquery\Items\LogicOr;
use Przeslijmi\Shortquery\Tools\InstancesFactory;
use Przeslijmi\Sexceptions\Sexception;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Shortquery\Data\Collection\Model;

/**
 * Parent for all model objects.
 */
abstract class Collection
{

    protected $model; // Model

    public function __construct()
    {

        $this->model = new Model($this);
    }

    public function getModel() : Model
    {

        return $this->model;
    }









    /**
     * Array of logics defined for this model.
     *
     * @var   LogicItem[]
     * @since v1.0
     */
    public $logics = [];


    /**
     * Getter for array of all logics.
     *
     * @return LogicItem[]
     * @since  v1.0
     */
    public function getLogics() : array
    {

        return $this->logics;
    }

    /**
     * Setter for adding new rule (and therefore also LogicAnd) to the model.
     *
     * @return self
     * @throws MethodFopException When creation of Rule have failed.
     * @since  v1.0
     */
    public function addRule() : self
    {

        try {
            $rule = Rule::make(...func_get_args());
        } catch (Sexception $e) {
            throw (new MethodFopException('creationOfRuleFailed', $e))->addInfos(func_get_args(), 'ruleArgs');
        }

        $logic = new LogicAnd($rule);
        $logic->setCollectionParent($this);

        $this->logics[] = $logic;

        return $this;
    }

    /**
     * Setter for adding new rule "equals" (and therefore also LogicAnd) to the model.
     *
     * @return self
     * @throws MethodFopException When creation of Rule have failed.
     * @since  v1.0
     */
    public function addRuleEq() : self
    {

        try {
            $ruleArgs = [ func_get_arg(0), 'eq', func_get_arg(1) ];
            $rule = Rule::make(...$ruleArgs);
        } catch (Sexception $e) {
            throw (new MethodFopException('creationOfRuleFailed', $e))->addInfos($ruleArgs, 'ruleArgs');
        }

        $logic = new LogicAnd($rule);
        $logic->setCollectionParent($this);

        $this->logics[] = $logic;

        return $this;
    }

    /**
     * Setter for adding new rule "not equals" (and therefore also LogicAnd) to the model.
     *
     * @return self
     * @throws MethodFopException When creation of Rule have failed.
     * @since  v1.0
     */
    public function addRuleNeq() : self
    {

        try {
            $ruleArgs = [ func_get_arg(0), 'neq', func_get_arg(1) ];
            $rule = Rule::make(...$ruleArgs);
        } catch (Sexception $e) {
            throw (new MethodFopException('creationOfRuleFailed', $e))->addInfos($ruleArgs, 'ruleArgs');
        }

        $logic = new LogicAnd($rule);
        $logic->setCollectionParent($this);

        $this->logics[] = $logic;

        return $this;
    }

    /**
     * Setter for adding new rule LogicOr to the model.
     *
     * @param array ...$rulesDefinitions Array of arrays with rules definitions.
     *
     * @return self
     * @since  v1.0
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










    private $objects = []; // object[]

    public function getObjects() : array
    {

        return $this->objects;
    }

    public function getObject(int $number=0) : object
    {

        return $this->objects[$number];
    }

    public function getObjectWithId($id) : object
    {


        foreach ($this->getObjects() as $object) {
            if ($object->getId() === $id) {
                return $object;
            }
        }

        die('sdfgdasdfjer492894q5wg4');
    }

    public function getValueForObjects(string $getterMethodName) : array
    {

        $result = [];

        foreach ($this->getObjects() as $object) {

            $result[] = $object->$getterMethodName();
        }

        return $result;
    }

    public function put(object $object) : void
    {

        $this->objects[] = $object;
    }

    public function putMany(array $objects) : void
    {

        foreach ($objects as $object) {
            $this->put($object);
        }
    }

    public function unpack(Collection $newCollection, Relation $relation)
    {

        $fieldFromGetter = $relation->getFieldFrom()->getGetterName();
        $fieldToGetter = $relation->getFieldTo()->getGetterName();
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
     *
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
     *
     * @return array Array of plain records from db.
     */
    public function readOneRecord() : array
    {

        $records = $this->readRecords();

        return array_slice($records, 0, 1)[0];
    }

    /**
     * Calls engine to get/select/read data.
     *
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
     * Calls engine to get/select/read data.
     *
     * @return array Array of Instances (ef. Car[]) with records.
     */
    public function readOne() : object
    {

        $record = $this->readOneRecord();

        $object = InstancesFactory::fromArray($this->model->getInstanceName(), $record);
        $this->objects[] = $object;

        return $object;
    }

    public function create() : void
    {

        $engine = new \Przeslijmi\Shortquery\Engine\MySql();
        $engine->setCollection($this);
        $records = $engine->create();
    }
}
