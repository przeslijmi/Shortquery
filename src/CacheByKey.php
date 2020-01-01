<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use Przeslijmi\Shortquery\Data\Instance;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Engine;
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
     * Constructor.
     *
     * @param string      $model     Name of model class to create cache on.
     * @param null|string $fieldName Name of field to use other field than pk.
     *
     * @since v1.0
     */
    public function __construct(string $model, ?string $fieldName)
    {

        // Save model.
        $this->model = new $model();

        // Save field name - if cache is to use other field than pk..
        if ($fieldName !== null) {
            $this->fieldOtherThanPk = $fieldName;
        }

        // Create select to limit results.
        $this->select = $this->model->newSelect();
    }

    /**
     * Getter for model.
     *
     * @since  v1.0
     * @return Model
     */
    public function getModel() : Model
    {

        return $this->model;
    }

    /**
     * Getter for select.
     *
     * @since  v1.0
     * @return Engine
     */
    public function getSelect() : Engine
    {

        return $this->select;
    }

    /**
     * Gathers data from databse.
     *
     * @since  v1.0
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
        $this->data = $this->select->readBy($fieldName);

        return $this;
    }

    /**
     * Get one record from already downloaded set.
     *
     * @param string|integer $keyValue       Value of primary key or other field (if used).
     * @param boolean        $throwOnMissing Optional, false. If set to true will throw on missing.
     *
     * @since  v1.0
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
            $instance->defineIsAdded(true);
            $instance->defineNothingChanged();

            // Mark that it was used already.
            $this->usedKeys[] = $keyValue;

            // Save to cache's cache.
            $this->instances[$keyValue] = $instance;

            return $instance;
        }

        // If data is not present and throwing is on.
        if ($data === null && $throwOnMissing === true) {
            throw new \Exception('Cache element missing ' . $keyValue . ' on ' . get_class($this->model) . ' (' . $this->fieldOtherThanPk . ').');
        }

        // If data was not present - create empty instance with this key value.

        // Find setter.
        if ($this->fieldOtherThanPk === null) {
            $setter = $this->model->getPkField()->getSetterName();
        } else {
            $field  = $this->model->getFieldByName($this->fieldOtherThanPk);
            $setter = $field->getSetterName();
        }

        // Set key value.
        $instance->$setter($keyValue);

        // Mark that it was used already.
        $this->usedKeys[] = $keyValue;

        // Save to cache's cache.
        $this->instances[$keyValue] = $instance;

        return $instance;
    }

    /**
     * Get record only once - this takeout will be registered and if you try do get it again, it will throw.
     *
     * @param string|integer $keyValue Value of primary key or other field (if used).
     *
     * @since  v1.0
     * @throws RecordAlreadyTakenOutFromCacheByPk If this record was already taken before.
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
     * @since  v1.0
     * @return self
     */
    public function markUsed($keyValue) : self
    {

        // Mark that it was used and takenout already.
        $this->usedKeys[] = $keyValue;

        return $this;
    }

    /**
     * Mark that record has been already takenout - without actually getting it.
     *
     * @param string|integer $keyValue Value of primary key or other field (if used).
     *
     * @since  v1.0
     * @throws RecordAlreadyTakenOutFromCacheByPk If this record was already taken before.
     * @return self
     */
    public function markTakenOut($keyValue) : self
    {

        // Throw if already taken.
        if (in_array($keyValue, $this->takenOutKeys)) {
            throw new RecordAlreadyTakenOutFromCacheByPk($keyValue, $this);
        }

        // Mark that it was used and takenout already.
        $this->usedKeys[]     = $keyValue;
        $this->takenOutKeys[] = $keyValue;

        return $this;
    }

    /**
     * Getter for all nonused key values - ie. all that was not downloaded by any method.
     *
     * @since  v1.0
     * @return string[]|integer[]
     */
    public function getNonUsedKeys() : array
    {

        return array_diff(array_keys($this->data), $this->usedKeys);
    }

    /**
     * Getter for all nontaken key values - ie. all that was not downloaded by `getOnce()`, nor `takeOut()`.
     *
     * @since  v1.0
     * @return string[]|integer[]
     */
    public function getNonTakenOutKeys() : array
    {

        return array_diff(array_keys($this->data), $this->takenOutKeys);
    }
}