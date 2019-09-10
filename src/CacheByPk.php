<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use Przeslijmi\Shortquery\Data\Instance;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Engine;
use Przeslijmi\Shortquery\Tools\InstancesFactory;
use Przeslijmi\Shortquery\Exceptions\Data\RecordAlreadyTakenOutFromCacheByPk;

/**
 * Cache for ShortQuery - gathering data by their primary key value.
 *
 * ## Usage example
 * ```
 * $cache = new CacheByPk('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel');
 * $cache->prepare();
 * echo $cache->get(1)->getName(); // will echo field name for record thats primary key is 1
 * ```
 */
class CacheByPk
{

    /**
     * Downloaded data.
     *
     * @var array
     */
    private $data = [];

    /**
     * Which PK's has been already read.
     *
     * @var array
     */
    private $usedPks = [];

    /**
     * Model which is downloaded by cache.
     *
     * @var Model
     */
    private $model;

    /**
     * Select query used by this model for this cache.
     *
     * @var Engine
     */
    private $select;

    /**
     * Constructor.
     *
     * @param string $model Name of model class to create cache on.
     *
     * @since v1.0
     */
    public function __construct(string $model)
    {

        // Save model.
        $this->model = new $model();

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

        // Get primary key field name.
        $pkFieldName = $this->model->getPkField()->getName();

        // Download data.
        $this->data = $this->select->readBy($pkFieldName);

        return $this;
    }

    /**
     * Get one record from already downloaded set.
     *
     * @param string|integer $pkValue Value of primary key.
     *
     * @since  v1.0
     * @return Instance
     */
    public function get($pkValue) : Instance
    {

        // Create new Instance.
        $instance = $this->model->getNewInstance();

        // Get contents to be poured to this Instance.
        $data = ( $this->data[$pkValue] ?? null );

        // If data is present.
        if ($data !== null) {

            // Artificially create instance.
            $instance = InstancesFactory::fromArray($instance, $data);
            $instance->defineIsAdded(true);
            $instance->defineNothingChanged();

            return $instance;
        }

        // If data was not present - create empty instance with this primary key.

        // Lvd.
        $pkSetter = $this->model->getPkField()->getSetterName();

        // Set primary key value.
        $instance->$pkSetter($pkValue);

        return $instance;
    }

    /**
     * Get record only once - this takeout will be registered and if you try do get it again, it will throw.
     *
     * @param string|integer $pkValue Value of primary key.
     *
     * @since  v1.0
     * @throws RecordAlreadyTakenOutFromCacheByPk If this record was already taken before.
     * @return Instance
     */
    public function getOnce($pkValue) : Instance
    {

        // Mark record taken out and throw if this happens not for the first time.
        $this->takeOut($pkValue);

        return $this->get($pkValue);
    }

    /**
     * Mark that record has been already takenout - without actually getting it..
     *
     * @param string|integer $pkValue Value of primary key.
     *
     * @since  v1.0
     * @throws RecordAlreadyTakenOutFromCacheByPk If this record was already taken before.
     * @return self
     */
    public function takeOut($pkValue) : self
    {

        // Throw if already taken.
        if (in_array($pkValue, $this->usedPks)) {
            throw new RecordAlreadyTakenOutFromCacheByPk($pkValue, $this);
        }

        // Mark that it was takenout already.
        $this->usedPks[] = $pkValue;

        return $this;
    }

    /**
     * Getter for all nonused pkValues - ie. all that was not downloaded by `getOnce()`, nor `takeOut()`.
     *
     * @since  v1.0
     * @return string[]|integer[]
     */
    public function getNonUsedPks() : array
    {

        return array_diff(array_keys($this->data), $this->usedPks);
    }
}
