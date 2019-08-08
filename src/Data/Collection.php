<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use Przeslijmi\Shortquery\Data\Collection\Logics;
use Przeslijmi\Shortquery\Data\Collection\Tools;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Exceptions\Records\CollectionSliceNotPossibleException;
use Przeslijmi\Shortquery\Items\LogicItem;
use Przeslijmi\Shortquery\Tools\InstancesFactory;

/**
 * Parent for all Collection of Instances (records).
 */
abstract class Collection extends Tools
{

    /**
     * Array of Records.
     *
     * @var Instance[]
     */
    private $instances = [];

    /**
     * Logics Collection for Collection.
     *
     * @var Logics
     */
    public $logics;

    /**
     * Model which is used by this Collection.
     *
     * @var Model
     */
    protected $model;

    public function __construct()
    {

        // Lvd.
        $this->getLogics()->add(...LogicItem::factory(...func_get_args()));
    }

    public function __clone()
    {

        // Reset instance.
        $this->logics = null;
        $this->instances = [];
    }

    /**
     * Getter for Model.
     *
     * @since  v1.0
     * @return Model
     */
    public function getModel() : Model
    {

        return $this->model;
    }

    /**
     * Getter for Logics.
     *
     * @since  v1.0
     * @return Logics
     */
    public function getLogics() : Logics
    {

        // Create empty Logics set if not exists.
        if (is_null($this->logics) === true) {
            $this->logics = new Logics($this);
        }

        return $this->logics;
    }

    /**
     * Getter for Instances.
     *
     * @param null|int $sliceFrom   Optional. If only part of results if needed - slice from (starting from 0).
     * @param null|int $sliceLength Optional. If only part of results if needed - slice length.
     *
     * @since  v1.0
     * @return Instance[]
     */
    public function get(?int $sliceFrom = null, ?int $sliceLength = null) : array
    {

        // If only slice of Instances is needed.
        if (is_null($sliceFrom) === false) {

            // Count minimum length of array that makes slice possible.
            $minLength = ( (int) $sliceFrom + (int) $sliceLength );

            // If this is not possible - throw.
            if (count($this->instances) < $minLength) {
                throw new CollectionSliceNotPossibleException($this, $sliceFrom, $sliceLength);
            }

            // If this is possible - return slice.
            return array_slice($this->instances, (int) $sliceFrom, $sliceLength);
        }

        return $this->instances;
    }

    public function getOne(?int $sliceFrom = 0) : Instance
    {

        $slice = $this->get($sliceFrom, 1);

        return $slice[0];
    }

    public function getByPk($pkValue) : ?Instance
    {

        foreach ($this->instances as $instance) {
            if ($instance->grabPkValue() === $pkValue) {
                return $instance;
            }
        }

        return null;
    }

    public function getByAdded() : array
    {

        // Lvd.
        $added    = [];
        $notAdded = [];

        // Test.
        foreach ($this->instances as $instance) {

            if ($instance->grabIsAdded() === true) {
                $added[] = $instance;
                continue;
            }

            $notAdded[] = $instance;
        }

        return [ $added, $notAdded ];
    }

    public function getByToBeDeleted() : array
    {

        // Lvd.
        $result = [];

        // Test.
        foreach ($this->instances as $instance) {
            if ($instance->grabIsToBeDeleted() === true) {
                $result[] = $instance;
            }
        }

        return $result;
    }

    public function length() : int
    {

        return count($this->instances);
    }

    /**
     * Getter of one value key for all Records.
     *
     * @param string  $fieldOrGetterName Name of the field or getter.
     * @param boolean $isThisGetter      Optional, false. Set to true if 0nd param is getter already.
     *
     * @since  v1.0
     * @return array
     */
    public function getValuesByField(string $fieldOrGetterName, bool $isThisGetter = false) : array
    {

        // Find getter name.
        if ($isThisGetter === false) {
            $getterMethodName = $this->getModel()->getFieldByName($fieldOrGetterName)->getGetterName();
        } else {
            $getterMethodName = $fieldOrGetterName;
        }

        // Lvd.
        $result = [];

        // Find values.
        foreach ($this->instances as $instance) {
            $result[] = $instance->$getterMethodName();
        }

        // Make unique.
        array_unique($result);

        return $result;
    }

    public function getGroupedByField(string $fieldOrGetterName, bool $isThisGetter = false) : array
    {

        // Lvd.
        $result = [];

        // Find getter name.
        if ($isThisGetter === false) {
            $getterMethodName = $this->getModel()->getFieldByName($fieldOrGetterName)->getGetterName();
        } else {
            $getterMethodName = $fieldOrGetterName;
        }

        // For every Instance.
        foreach ($this->get() as $instance) {

            // Find grouping string.
            $groupingString = $instance->$getterMethodName();

            // Create result for this grouping string.
            if (isset($result[$groupingString]) === false) {
                $result[$groupingString] = [];
            }

            // Add id to result.
            $result[$groupingString][] = $instance;
        }

        return $result;
    }

    /**
     * Add one Instance to Collection.
     *
     * @param Instance|Instance[] $instance Instance or array of Instances to be put.
     *
     * @since  v1.0
     * @return self
     */
    public function put($instanceOrInstances) : self
    {

        // Wrap in array.
        if (is_array($instanceOrInstances) === false) {
            $instances = [ $instanceOrInstances ];
        } else {
            $instances = $instanceOrInstances;
        }

        // Add every one.
        foreach ($instances as $instance) {
            $this->instances[] = $instance;
        }

        return $this;
    }

    public function putRecord(array $record) : self
    {

        $instanceClass = $this->getModel()->getClass('instanceClass');
        $this->put(InstancesFactory::fromArray($instanceClass, $record));

        return $this;
    }

    public function putRecords(array $records) : self
    {

        $instanceClass = $this->getModel()->getClass('instanceClass');

        foreach ($records as $record) {
            $this->put(InstancesFactory::fromArray($instanceClass, $record));
        }

        return $this;
    }

    /**
     * Not sure what it does.
     *
     * @param Collection $newCollection
     * @param Relation   $relation
     *
     * @since  v1.0
     * @return self
     */
    public function unpack(Collection $newCollection, Relation $relation) : self
    {

        // Lvd.
        if ($relation->getType() === 'hasMany') {
            return $this->unpackHasMany($newCollection, $relation);
        }

        return $this->unpackHasOne($newCollection, $relation);
    }

    public function unpackHasMany(Collection $newCollection, Relation $relation) : self
    {

        // Lvd.
        $fieldFrom       = $relation->getFieldFrom()->getName();
        $fieldToGetter   = $relation->getFieldTo()->getGetterName();
        $adderMethodName = $relation->getAdderName();

        // Get grouped old/current Collection.
        $groupedOld = $this->getGroupedByField($fieldFrom);

        // Get grouped new Collection.
        $newCollections = $newCollection->splitByField($relation->getFieldTo());

        // For every Collection in new collections (children).
        foreach ($newCollections as $keyTo => $newCollection) {
            foreach ($groupedOld[$keyTo] as $oldInstance) {
                $oldInstance->$adderMethodName($newCollection);
            }
        }

        return $this;
    }

    public function unpackHasOne(Collection $newCollection, Relation $relation) : self
    {

        // Lvd.
        $fieldFrom       = $relation->getFieldFrom()->getName();
        $fieldToGetter   = $relation->getFieldTo()->getGetterName();
        $adderMethodName = $relation->getAdderName();

        // Get grouped old/current Collection.
        $groupedOld = $this->getGroupedByField($fieldFrom);

        // For every Record in new collection (children)
        foreach ($newCollection->get() as $newInstance) {

            // Key of new Instance (key in child table).
            $keyTo = $newInstance->$fieldToGetter();

            foreach ($groupedOld[$keyTo] as $oldInstance) {
                $oldInstance->$adderMethodName($newInstance);
            }
        }

        return $this;
    }

    public function clear() : self
    {

        $this->instances = [];

        return $this;
    }

    public function clearNonAdded() : self
    {

        foreach ($this->instances as $id => $instance) {
            if ($instance->grabIsAdded() === false) {
                unset($this->instances[$id]);
            }
        }

        return $this;
    }

    /**
     * Gets Records from DB and puts them to Collection as Instances.
     *
     * @since  v1.0
     * @return array Array of plain Records from db.
     */
    public function read(?int $sliceFrom = null, ?int $sliceLength = null, $fieldOrFields = null) : self
    {

        // Create SELECT Query.
        $select = $this->getModel()->newSelect();
        $select->setLogicsSet($this->getLogics()->get());

        // Add LIMIT to Query.
        if (is_null($sliceFrom) === false) {
            $select->setLimit((int) $sliceFrom, (int) $sliceLength);
        }

        // Add ORDER to Query.
        if ($fieldOrFields !== null) {

            if (is_array($fieldOrFields) === false) {
                $fieldOrFields = [ $fieldOrFields ];
            }

            foreach ($fieldOrFields as $orderByField) {
                $select->addField($orderByField, false, true);
            }
        }

        // Make reading.
        $select->readIntoCollection($this);

        return $this;
    }

    public function readOrderedBy($fieldOrFields, ?int $sliceFrom = null, ?int $sliceLength = null) : self
    {

        return $this->read($sliceFrom, $sliceLength, $fieldOrFields);
    }

    public function count($aggregationFields = []) : array
    {

        $result = [
            'all' => 0
        ];

        // Create SELECT Query.
        $select = $this->getModel()->newSelect();

        foreach ($aggregationFields as $field) {
            $select->addField($field, true, true);
        }
        $select->addFunc('count', [])->setAlias('counter');
        $select->setLogicsSet($this->getLogics()->get());

        return $select->read();
    }

    /**
     * Update existing Record.
     *
     * @since  v1.0
     * @return self
     */
    public function update(?array $differentSetOfInstances = null) : self
    {

        // Create UPDATE Query.
        $update = $this->getModel()->newUpdate();

        // Add Logics.
        $update->setLogicsSet($this->getLogics()->get());

        // Add all Instances.
        if (is_null($differentSetOfInstances) === true) {
            $update->setInstances($this->get());
        } else {
            $update->setInstances($differentSetOfInstances);
        }

        // Fire Query.
        $update->fire();

        return $this;
    }

    /**
     * Calls engine to insert data.
     *
     * @since  v1.0
     * @return void
     */
    public function create(?array $differentSetOfInstances = null) : void
    {

        // Create INSERT Query.
        $insert = $this->getModel()->newInsert();

        // Add all Instances.
        if (is_null($differentSetOfInstances) === true) {
            $insert->setInstances($this->get());
        } else {
            $insert->setInstances($differentSetOfInstances);
        }

        // Fire Query.
        $insert->fire();
    }

    public function delete(?array $differentSetOfInstances = null) : void
    {

        // Create DELETE Query.
        $delete = $this->getModel()->newDelete();
        $delete->setLogicsSet($this->getLogics()->get());

        // Add all Instances.
        if (is_null($differentSetOfInstances) === true) {
            $delete->setInstances($this->get());
        } else {
            $delete->setInstances($differentSetOfInstances);
        }

        // Fire Query.
        $delete->fire();
    }


    /**
     * Calls engine to insert or update data.
     *
     * @since  v1.0
     * @return void
     */
    public function save() : void
    {

        list($instancesAdded, $instancesNotAdded) = $this->getByAdded();

        if (count($instancesAdded) > 0) {
            $this->update($instancesAdded);
        }

        if (count($instancesNotAdded) > 0) {
            $this->create($instancesNotAdded);
        }

        $instancesToBeDeleted = $this->getByToBeDeleted();

        if (count($instancesToBeDeleted) > 0) {
            $this->delete($instancesToBeDeleted);
        }
    }
}
