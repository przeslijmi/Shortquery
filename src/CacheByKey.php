<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use Exception;
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
        $this->data = $this->select->readBy($fieldName);

        return $this;
    }

    /**
     * Get one record from already downloaded set.
     *
     * @param string|integer $keyValue       Value of primary key or other field (if used).
     * @param boolean        $throwOnMissing Optional, false. If set to true will throw on missing.
     *
     * @throws Exception When element from cache is missing.
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

            // Lvd.
            $text  = 'Cache element missing ' . $keyValue . ' on ';
            $text .= get_class($this->model) . ' (' . $this->fieldOtherThanPk . ').';

            // Throw.
            throw new Exception($text);
        }

        // If data was not present - create empty instance with this key value.
        // Setter of key field will be used only if non-array key was given.
        $setter = null;

        // Find setter.
        if ($this->fieldOtherThanPk === null) {
            $setter = $this->model->getPkField()->getSetterName();
        } else {
            $field  = $this->model->getFieldByName($this->fieldOtherThanPk);
            $setter = $field->getSetterName();
        }

        // Set key value.
        if ($setter !== null) {
            $instance->$setter($keyValue);
        }

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
        $this->usedKeys[] = $keyValue;

        return $this;
    }

    /**
     * Mark that record has been already takenout - without actually getting it.
     *
     * @param string|integer $keyValue Value of primary key or other field (if used).
     *
     * @throws RecordAlreadyTakenOutFromCacheByKey If this record was already taken before.
     * @return self
     */
    public function markTakenOut($keyValue) : self
    {

        // Throw if already taken.
        if (in_array($keyValue, $this->takenOutKeys) === true) {
            throw new RecordAlreadyTakenOutFromCacheByKey($keyValue, $this);
        }

        // Mark that it was used and takenout already.
        $this->usedKeys[]     = $keyValue;
        $this->takenOutKeys[] = $keyValue;

        return $this;
    }

    /**
     * Getter for all nonused key values - ie. all that was not downloaded by any method.
     *
     * @return string[]|integer[]
     */
    public function getNonUsedKeys() : array
    {

        return array_diff(array_keys($this->data), $this->usedKeys);
    }

    /**
     * Getter for all nontaken key values - ie. all that was not downloaded by `getOnce()`, nor `takeOut()`.
     *
     * @return string[]|integer[]
     */
    public function getNonTakenOutKeys() : array
    {

        return array_diff(array_keys($this->data), $this->takenOutKeys);
    }
}
