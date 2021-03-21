<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use Przeslijmi\Shortquery\Data\Instance;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Engine;
use Przeslijmi\Shortquery\Exceptions\CacheByKey\CacheElementMissingException;
use Przeslijmi\Shortquery\Exceptions\Data\RecordAlreadyTakenOutFromCacheByKey;
use Przeslijmi\Shortquery\Tools\InstancesFactory;

/**
 * Cache for ShortQuery - gathering data by their (primary) key value.
 *
 * ## Usage example
 * ```
 * $cache = new CacheByPk('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel');
 * $cache->prepare();
 * echo $cache->get(1)->getName(); // will echo field name for record thats primary key is 1
 * ```
 */
class CacheByKey
{

    /**
     * Downloaded data.
     *
     * @var array
     */
    private $data = [];

    /**
     * Downloaded children data (first key is a relation name, second key is parent/cache/data key).
     *
     * @var array
     */
    private $dataChildren = [];

    /**
     * Created instances.
     *
     * @var Instance[]
     */
    private $instances = [];

    /**
     * Which PK's (or other key field) has been already read.
     *
     * @var scalar[]
     */
    private $usedKeys = [];

    /**
     * Which PK's (or other key field) has been already read and taken out.
     *
     * @var scalar[]
     */
    private $takenOutKeys = [];

    /**
     * Model which is downloaded by cache.
     *
     * @var Model
     */
    private $model;

    /**
     * Field which is used for cache (by default it is a pk field, ie. null).
     *
     * @var null|string
     */
    private $fieldOtherThanPk = null;

    /**
     * Select query used by this model for this cache.
     *
     * @var Engine
     */
    private $select;

    /**
     * Definitions of childrens that has to be delivered among delivering parent (cache element).
     *
     * @var array
     */
    private $children = [];

    /**
     * Constructor.
     *
     * @param string            $model            Name of model class to create cache on.
     * @param null|string|array $fieldNameOrNames Optional. Name of field/fields to use if other than field pk.
     */
    public function __construct(string $model, ?string $fieldNameOrNames = null)
    {

        // Save model.
        $this->model = new $model();

        // Save field name - if cache is to use other field than pk..
        if ($fieldNameOrNames !== null) {
            $this->fieldOtherThanPk = $fieldNameOrNames;
        }

        // Create select to limit results.
        $this->select = $this->model->newSelect();
    }

    /**
     * Getter for model.
     *
     * @return Model
     */
    public function getModel() : Model
    {

        return $this->model;
    }

    /**
     * Getter for select.
     *
     * @return Engine
     */
    public function getSelect() : Engine
    {

        return $this->select;
    }

    /**
     * Gathers data from databse.
     *
     * @return self
     */
    public function prepare() : self
    {

        // Get field to use.
        if ($this->fieldOtherThanPk === null) {
            $fieldName = $this->model->getPkField()->getName();
        } else {
            $fieldName = $this->fieldOtherThanPk;
        }

        // Download data.
        $this->data = $this->select->readBy($fieldName, true);

        // Download data for children.
        foreach ($this->children as $relationName => $relationInfo) {

            // Lvd.
            $relation = $relationInfo['relation'];
            $pks      = array_column($this->data, $relationInfo['fieldOld']->getName());

            // Get select.
            $relationInfo['select']->addRule($relationInfo['fieldNew']->getName(), $pks);
            $this->dataChildren[$relationName] = $relationInfo['select']->readMultipleBy(
                $relationInfo['fieldNew']->getName()
            );
        }

        return $this;
    }

    /**
     * Get one record from already downloaded set.
     *
     * @param string|integer $keyValue       Value of primary key or other field (if used).
     * @param boolean        $throwOnMissing Optional, false. If set to true will throw on missing.
     *
     * @throws CacheElementMissingException When element from cache is missing.
     * @return Instance
     */
    public function get($keyValue, bool $throwOnMissing = false) : Instance
    {

        // Take from cache's cache.
        if (isset($this->instances[$keyValue]) === true) {
            return $this->instances[$keyValue];
        }

        // Create new Instance.
        $instance = $this->model->getNewInstance();

        // Get contents to be poured to this Instance.
        $data = ( $this->data[$keyValue] ?? null );

        // If data is present.
        if ($data !== null) {

            // Artificially create instance.
            $instance = InstancesFactory::fromArray($instance, $data);

            // Artificially create childrens instance.
            foreach ($this->children as $relationName => $relationInfo) {

                // Get collection object.
                $collection  = new $relationInfo['collection']();
                $adderName   = $relationInfo['relation']->getAdderName();
                $keyRelValue = $data[$relationInfo['fieldOld']->getName()];
                $children    = ( $this->dataChildren[$relationName][$keyRelValue] ?? [] );

                // Every one child.
                foreach ($children as $child) {
                    $childInstance = $relationInfo['model']->getNewInstance();
                    $childInstance = InstancesFactory::fromArray($childInstance, $child);
                    $childInstance->defineIsAdded(true);
                    $childInstance->defineNothingChanged();
                    $collection->put($childInstance);
                }

                // Save children to parents.
                $instance->$adderName($collection);
            }

            // Finish instance.
            $instance->defineIsAdded(true);
            $instance->defineNothingChanged();

            // Mark that it was used already.
            $this->usedKeys[$keyValue] = true;

            // Save to cache's cache.
            $this->instances[$keyValue] = $instance;

            return $instance;
        }//end if

        // If data is not present and throwing is on.
        if ($data === null && $throwOnMissing === true) {
            throw new CacheElementMissingException([
                get_class($this->model),
                $this->fieldOtherThanPk,
                $keyValue,
            ]);
        }

        // If data was not present - create empty instance with this key value.
        // Setter of key field will be used only if non-array key was given.
        $setter = null;

        // Find setter.
        if ($this->fieldOtherThanPk === null) {
            $setter = $this->model->getPkField()->getSetterName();
        } else {
            $field = $this->model->getFieldByNameIfExists($this->fieldOtherThanPk);
            if ($field !== null) {
                $setter = $field->getSetterName();
            }
        }

        // Set key value.
        if ($setter !== null) {
            $instance->$setter($keyValue);
        }

        // Mark that it was used already.
        $this->usedKeys[$keyValue] = true;

        // Save to cache's cache.
        $this->instances[$keyValue] = $instance;

        return $instance;
    }

    /**
     * Get record only once - this takeout will be registered and if you try do get it again, it will throw.
     *
     * @param string|integer $keyValue Value of primary key or other field (if used).
     *
     * @throws RecordAlreadyTakenOutFromCacheByKey If this record was already taken before.
     * @return Instance
     */
    public function getOnce($keyValue) : Instance
    {

        // Mark record taken out and throw if this happens not for the first time.
        $this->markTakenOut($keyValue);

        return $this->get($keyValue);
    }

    /**
     * Mark that record has been already used - without actually using it.
     *
     * @param string|integer $keyValue Value of primary key or other field (if used).
     *
     * @return self
     */
    public function markUsed($keyValue) : self
    {

        // Mark that it was used and takenout already.
        $this->usedKeys[$keyValue] = true;

        return $this;
    }

    /**
     * Mark that record has been already taken out - without actually getting it.
     *
     * @param string|integer $keyValue Value of primary key or other field (if used).
     *
     * @throws RecordAlreadyTakenOutFromCacheByKey If this record was already taken before.
     * @return self
     */
    public function markTakenOut($keyValue) : self
    {

        // Throw if already taken.
        if (isset($this->takenOutKeys[$keyValue]) === true) {
            throw new RecordAlreadyTakenOutFromCacheByKey(
                [ (string) $keyValue, get_class($this->getModel()), $this->getModel()->getName() ]
            );
        }

        // Mark that it was used and takenout already.
        $this->usedKeys[$keyValue]     = true;
        $this->takenOutKeys[$keyValue] = true;

        return $this;
    }

    /**
     * Mark (overwrite) that record has NOT been taken out - no matter on real situation.
     *
     * @param string|integer $keyValue Value of primary key or other field (if used).
     *
     * @return self
     */
    public function markNotTakenOut($keyValue) : self
    {

        if (isset($this->usedKeys[$keyValue]) === true) {
            unset($this->usedKeys[$keyValue]);
        }
        if (isset($this->takenOutKeys[$keyValue]) === true) {
            unset($this->takenOutKeys[$keyValue]);
        }

        return $this;
    }

    /**
     * It frees memory for one key value by deleting created instance for this key.
     *
     * @param string|integer $keyValue Value of primary key or other field (if used).
     *
     * @return self
     */
    public function freeMemory($keyValue) : self
    {

        // Take from cache's cache.
        if (isset($this->instances[$keyValue]) === true) {
            $this->instances[$keyValue] = null;
            unset($this->instances[$keyValue]);
        }

        return $this;
    }

    /**
     * Getter for all nonused key values - ie. all that was not downloaded by any method.
     *
     * @return string[]|integer[]
     */
    public function getNonUsedKeys() : array
    {

        return array_diff(array_keys($this->data), array_keys($this->usedKeys));
    }

    /**
     * Getter for all nontaken key values - ie. all that was not downloaded by `getOnce()`, nor `takeOut()`.
     *
     * @return string[]|integer[]
     */
    public function getNonTakenOutKeys() : array
    {

        return array_diff(array_keys($this->data), array_keys($this->takenOutKeys));
    }

    /**
     * Define need to deliver childrens (has many relation contents) among delivering parent (cache element).
     *
     * @param string $relationName Name of relation to be used.
     *
     * @return Engine
     */
    public function addChildren(string $relationName) : Engine
    {

        // Lvd.
        $relation   = $this->model->getRelationByName($relationName);
        $model      = $relation->getModelOtherThan($this->model->getName());
        $fieldNew   = $relation->getFieldFromModelOtherThan($this->model->getName());
        $fieldOld   = $relation->getFieldFromModelOtherThan($model->getName());
        $select     = $model->newSelect();
        $collection = $model->getClass('collectionClass');

        // Save.
        $this->children[$relationName] = [
            'relation' => $relation,
            'model' => $model,
            'fieldNew' => $fieldNew,
            'fieldOld' => $fieldOld,
            'select' => $select,
            'collection' => $collection,
        ];

        // Prepare data.
        $this->dataChildren[$relationName] = [];

        return $select;
    }

    /**
     * Returns pure data from Cache (that was downloaded from database).
     *
     * @return array
     */
    public function getData() : array
    {

        return $this->data;
    }
}
