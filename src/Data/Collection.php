<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use Przeslijmi\Sexceptions\Sexception;
use Przeslijmi\Shortquery\Data\Collection\Logics;
use Przeslijmi\Shortquery\Data\Collection\Tools;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Exceptions\Data\CollectionCantBeCreatedException;
use Przeslijmi\Shortquery\Exceptions\Data\CollectionCantBeReadException;
use Przeslijmi\Shortquery\Exceptions\Data\CollectionSliceNotPossibleException;
use Przeslijmi\Shortquery\Items\LogicItem;
use Przeslijmi\Shortquery\Tools\InstancesFactory;
use Throwable;

/**
 * Parent for all Collection of Instances (records).
 */
abstract class Collection extends Tools
{

    /**
     * Array of Records.
     *
     * @var   Instance[]
     * @since v1.0
     */
    private $instances = [];

    /**
     * Logics Collection for Collection.
     *
     * @var   Logics
     * @since v1.0
     */
    public $logics;

    /**
     * Model which is used by this Collection. Defined in *CollectionCore class.
     *
     * @var   Model
     * @since v1.0
     */
    protected $model;

    /**
     * Constructor.
     *
     * @since v1.0
     */
    public function __construct()
    {

        // Create logics - if it was asked for.
        try {
            $this->getLogics()->add(...LogicItem::factory(...func_get_args()));
        } catch (Throwable $thr) {
            throw new CollectionCantBeCreatedException(
                get_class($this),
                func_get_args(),
                $thr
            );
        }
    }

    /**
     * Magic method for clone - clean logics and instances.
     *
     * @since v1.0
     */
    public function __clone()
    {

        // Reset instance.
        $this->logics    = null;
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
     * @param null|integer $sliceFrom   Optional. If only part of results if needed - slice from (starting from 0).
     * @param null|integer $sliceLength Optional. If only part of results if needed - slice length.
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

    /**
     * Returns one Instance starting from given position (negative start from end).
     *
     * @param integer $sliceFrom Optional. Which instance has it to be.
     *
     * @since  v1.0
     * @return Instance
     */
    public function getOne(?int $sliceFrom = 0) : Instance
    {

        return $this->get($sliceFrom, 1)[0];
    }

    /**
     * Returns first instance with given value in Primary Key.
     *
     * @param string|integer $pkValue Value of primary key.
     *
     * @since  v1.0
     * @return null|Instance
     */
    public function getByPk($pkValue) : ?Instance
    {

        // Look and return if found.
        foreach ($this->instances as $instance) {
            if ($instance->grabPkValue() === $pkValue) {
                return $instance;
            }
        }

        return null;
    }

    /**
     * Return array with two arrays with instances, where [0] is for added, [1] is for nonadded.
     *
     * @since  v1.0
     * @return array
     */
    public function getByAdded() : array
    {

        // Lvd.
        $added    = [];
        $notAdded = [];

        // Test.
        foreach ($this->instances as $instance) {

            // This goes to added.
            if ($instance->grabIsAdded() === true) {
                $added[] = $instance;
                continue;
            }

            // This goes to nonadded.
            $notAdded[] = $instance;
        }

        return [ $added, $notAdded ];
    }

    /**
     * Return array with Instances that are set to be deleted.
     *
     * @since  v1.0
     * @return Instance[]
     */
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

    /**
     * Return array with Instances gruped in subarrays - which key's are desired field values.
     *
     * @param string  $fieldOrGetterName Name of the field or getter.
     * @param boolean $isThisGetter      Optional, false. Set to true if 0nd param is getter already.
     *
     * @since  v1.0
     * @return Instances[]
     */
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
     * Return length of Collection.
     *
     * @since  v1.0
     * @return integer
     */
    public function length() : int
    {

        return count($this->instances);
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

    /**
     * Put array record (not Instance) into Collection.
     *
     * @param array $record Array record to be put in.
     *
     * @since  v1.0
     * @return self
     */
    public function putRecord(array $record) : self
    {

        // Lvd.
        $instanceClass = $this->getModel()->getClass('instanceClass');

        // Put.
        $this->put(InstancesFactory::fromArray($instanceClass, $record));

        return $this;
    }

    /**
     * Put many array records (not Instances) into Collection.
     *
     * @param array $record Array records to be put in.
     *
     * @since  v1.0
     * @return self
     */
    public function putRecords(array $records) : self
    {

        // Lvd.
        $instanceClass = $this->getModel()->getClass('instanceClass');

        // Put for each.
        foreach ($records as $record) {
            $this->put(InstancesFactory::fromArray($instanceClass, $record));
        }

        return $this;
    }

    /**
     * Not sure what it does.
     *
     * @param Collection $children Children fitting to all parents (ie. Instances of `$this`).
     * @param Relation   $relation Relation that explains by which Field Children and Parent are matched.
     *
     * @since  v1.0
     * @return self
     */
    public function unpack(Collection $children, Relation $relation) : self
    {

        // If this is Relation `hasMany` (ie. every parent can have more than one child).
        if ($relation->getType() === 'hasMany') {
            return $this->unpackHasMany($children, $relation);
        }

        // Or if this is Relation `hasOne` (ie. every parent can have at most one child).
        return $this->unpackHasOne($children, $relation);
    }

    /**
     * Adds to every Instance in this Collection child records (basing on Relation).
     *
     * For example - `$this` is a `Plane`. And `$children` are `Seats`. This methods
     * receives Collection of all `Seats` from all `Planes` and checks to which `Seat`
     * has to be unpacked (added) to which `Plane` in Collection.
     *
     * @param Collection $children Children fitting to all parents (ie. fitting to Instances of `$this`).
     * @param Relation   $relation Relation that explains by which Field Children and Parent are matched.
     *
     * @since  v1.0
     * @return self
     */
    public function unpackHasMany(Collection $children, Relation $relation) : self
    {

        // Lvd.
        $fieldFrom       = $relation->getFieldFrom()->getName();
        $fieldToGetter   = $relation->getFieldTo()->getGetterName();
        $adderMethodName = $relation->getAdderName();

        // Get parents Collection splitted with joining field (most propabely ... parent primary key).
        $parentsSplitted = $this->getGroupedByField($fieldFrom);

        // Get children Collection splitted with joining field (most propabely ... parent primary key).
        $childrens = $children->splitByField($relation->getFieldTo());

        // Now put every Children to proper parent ... basig on joining fields.
        foreach ($childrens as $keyTo => $children) {
            foreach ($parentsSplitted[$keyTo] as $parentInstance) {
                $parentInstance->$adderMethodName($children);
            }
        }

        return $this;
    }

    /**
     * Adds to every Instance in this Collection at most one child records (basing on Relation).
     *
     * For example - `$this` is a `Plane`. And `$children` are `MainPilots`. This methods
     * receives Collection of all `MainPilots` from all `Planes` and checks to which `MainPilot`
     * has to be unpacked (added) to which `Plane` in Collection.
     *
     * @param Collection $children Children fitting to all parents (ie. fitting to Instances of `$this`).
     * @param Relation   $relation Relation that explains by which Field Children and Parent are matched.
     *
     * @since  v1.0
     * @return self
     */
    public function unpackHasOne(Collection $children, Relation $relation) : self
    {

        // Lvd.
        $fieldFrom       = $relation->getFieldFrom()->getName();
        $fieldToGetter   = $relation->getFieldTo()->getGetterName();
        $adderMethodName = $relation->getAdderName();

        // Get parents Collection splitted with joining field (most propabely ... parent primary key).
        $parentsSplitted = $this->getGroupedByField($fieldFrom);

        // For every Children find what is it's Parent and connect.
        foreach ($children->get() as $childInstance) {

            // Key of new Instance (key in child table).
            $keyTo = $childInstance->$fieldToGetter();

            // Put this child to every Parent needed.
            foreach ($parentsSplitted[$keyTo] as $parentInstance) {
                $parentInstance->$adderMethodName($childInstance);
            }
        }

        return $this;
    }

    /**
     * Deletes all Instances from this Collection.
     *
     * @since  v1.0
     * @return self
     */
    public function clear() : self
    {

        $this->instances = [];

        return $this;
    }

    /**
     * Gets Records from DB and puts them to Collection as Instances.
     *
     * @param null|integer $sliceFrom   Optional. If only part of results if needed - slice from (starting from 0).
     * @param null|integer $sliceLength Optional. If only part of results if needed - slice length.
     * @param string|array $orderBys     Optional. Field or fields to used for ordering.
     *
     * @since  v1.0
     * @return array Array of plain Records from db.
     */
    public function read(?int $sliceFrom = null, ?int $sliceLength = null, $orderBys = '') : self
    {

        try {

            // Create SELECT Query.
            $select = $this->getModel()->newSelect();
            $select->setLogicsSet($this->getLogics()->get());

            // Add LIMIT to Query.
            if (is_null($sliceFrom) === false) {
                $select->setLimit((int) $sliceFrom, (int) $sliceLength);
            }

            // Add ORDER to Query.
            if (empty($orderBys) === false) {

                // Wrap array around.
                if (is_array($orderBys) === false) {
                    $orderBys = [ $orderBys ];
                }

                // For each - add order field.
                foreach ($orderBys as $orderBy) {
                    $select->addField($orderBy, false, true);
                }
            }

            // Make reading.
            $select->readIntoCollection($this);

        } catch (Throwable $thr) {
            throw new CollectionCantBeReadException(get_class($this), $thr);
        }

        return $this;
    }

    public function readOrderedBy($fieldOrFields, ?int $sliceFrom = null, ?int $sliceLength = null) : self
    {

        return $this->read($sliceFrom, $sliceLength, $fieldOrFields);
    }

    /**
     * Counts how many records there are in this collection. Serves aggregation fields.
     *
     * ## Usage example.
     *
     * This will return array with one key `@@total` and integer number of records.
     * ```
     * $collection->count();
     * ```
     *
     * This will return array with key `@@total` but also key for every unique value of pair
     * of those two fields joined with backlash.
     * ```
     * $collection->count([ 'field1', 'field2' ]);
     * ```
     *
     * This just changes backslash seprarator to dot separator.
     * ```
     * $collection->count([ 'field1', 'field2' ], '.');
     * ```
     *
     * @param array  $aggregationFields Optional, empty. Fields that has to be used for aggregation.
     * @param string $separator         Optional, backlash. Used to join aggregators values to create keys.
     *
     * @since  v1.0
     * @return integer[]
     */
    public function count(array $aggregationFields = [], string $separator = '\\') : array
    {

        // Lvd.
        $result = [
            '@@total' => 0
        ];

        // Create SELECT Query.
        $select = $this->getModel()->newSelect();

        // Add agregation fields.
        foreach ($aggregationFields as $field) {
            $select->addField($field, true, true, true);
        }

        // Add function and rest of logics.
        $select->addFunc('count', [])->setAlias('counter');
        $select->setLogicsSet($this->getLogics()->get());

        // Analyse results.
        foreach ($select->read() as $record) {

            // Amount for this group.
            $counter = (int) $record['counter'];

            // Find name of the group (summarized contents of GROUP BY columns).
            unset($record['counter']);
            $key = implode($separator, $record);

            // If the key is empty - it means no aggregaiton columns where used - so adding this result
            // among @@total result will be just a duplication.
            if (empty($key) === false) {
                $result[$key] = $counter;
            }

            // Sum up to @@total columns.
            $result['@@total'] += $counter;
        }

        return $result;
    }

    /**
     * Calls engine to update existing records.
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
     * Calls engine to insert records.
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
        $insert->call();
    }

    /**
     * Calls engine to delete records.
     *
     * @since  v1.0
     * @return void
     */
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
     * Calls engine to insert, update or delete records.
     *
     * @since  v1.0
     * @return void
     */
    public function save() : void
    {

        // Get what has to be added and what has to be updated.
        list($instancesAdded, $instancesNotAdded) = $this->getByAdded();

        // Update.
        if (count($instancesAdded) > 0) {
            $this->update($instancesAdded);
        }

        // Add.
        if (count($instancesNotAdded) > 0) {
            $this->create($instancesNotAdded);
        }

        // Get what have to be deleted.
        $instancesToBeDeleted = $this->getByToBeDeleted();

        // Delete.
        if (count($instancesToBeDeleted) > 0) {
            $this->delete($instancesToBeDeleted);
        }
    }
}
