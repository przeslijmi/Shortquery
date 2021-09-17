<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\ForTests\Models\Core;

use Przeslijmi\Shortquery\Data\Instance;
use Przeslijmi\Shortquery\Exceptions\Data\CollectionSliceNotPossibleException;
use Przeslijmi\Shortquery\ForTests\Models\Core\ThingModel;
use Przeslijmi\Shortquery\ForTests\Models\Thing;
use Przeslijmi\Shortquery\ForTests\Models\Things;
use Przeslijmi\Shortquery\Tools\InstancesFactory;
use stdClass;

/**
 * ShortQuery Core class for Thing Model.
 */
class ThingCore extends Instance
{

    /**
     * Field `pk`.
     *
     * @var integer
     */
    private $pk;

    /**
     * Field `name`.
     *
     * @var null|string
     */
    private $name;

    /**
     * Field `json_data`.
     *
     * @var null|stdClass
     */
    private $jsonData;

    /**
     * Constructor.
     *
     * @param string $database Optional, `null`. In which database this field is defined.
     */
    public function __construct(?string $database = null)
    {

        // Get model Instance.
        $this->model = ThingModel::getInstance();

        // Set database if given.
        $this->database = $database;
    }

    /**
     * Fast data injector.
     *
     * @param array $inject Data to be injected to object.
     *
     * @return self
     */
    public function injectData(array $inject) : self
    {

        // Inject properties.
        if (isset($inject['pk']) === true && $inject['pk'] !== null) {
            $this->pk = (int) $inject['pk'];
        }
        if (isset($inject['name']) === true && $inject['name'] !== null) {
            $this->name = (string) $inject['name'];
        }
        if (isset($inject['json_data']) === true && $inject['json_data'] !== null) {
            $this->jsonData = $inject['json_data'];
        }

        // Mark all fields set.
        $this->setFields = array_keys($inject);

        return $this;
    }

    /**
     * Returns info if primary key for this record has been given.
     *
     * @return boolean
     */
    public function hasPrimaryKey() : bool
    {

        if ($this->pk === null) {
            return false;
        }

        return true;
    }

    /**
     * Resets primary key into null - like the record is not existing in DB.
     *
     * @return self
     */
    protected function resetPrimaryKey() : self
    {

        $this->pk = null;

        $noInSet = array_search('pk', $this->setFields);

        if (is_int($noInSet) === true) {
            unset($this->setFields[$noInSet]);
        }

        return $this;
    }

    /**
     * Getter for `pk` field value.
     *
     * @return integer
     */
    public function getPk() : int
    {

        return $this->getCorePk(...func_get_args());
    }

    /**
     * Core getter for `pk` field value.
     *
     * @return integer
     */
    public function getCorePk() : int
    {

        return $this->pk;
    }

    /**
     * Setter for `pk` field value.
     *
     * @param integer $pk Value to be set.
     *
     * @return Thing
     */
    public function setPk(int $pk) : Thing
    {

        return $this->setCorePk($pk);
    }

    /**
     * Core setter for `pk` field value.
     *
     * @param integer $pk Value to be set.
     *
     * @return Thing
     */
    public function setCorePk(int $pk) : Thing
    {

        // Test value.
        $this->grabField('pk')->isValueValid($pk);

        // If there is nothing to be changed.
        if ($this->pk === $pk) {
            return $this;
        }

        // Save.
        $this->pk = $pk;

        // Note that was set.
        $this->setFields[]     = 'pk';
        $this->changedFields[] = 'pk';

        // Note that was changed.
        if (isset($this->fieldsValuesHistory['pk']) === false) {
            $this->fieldsValuesHistory['pk'] = [];
        }
        $this->fieldsValuesHistory['pk'][] = $pk;

        return $this;
    }

    /**
     * Getter for `name` field value.
     *
     * @return null|string
     */
    public function getName() : ?string
    {

        return $this->getCoreName(...func_get_args());
    }

    /**
     * Core getter for `name` field value.
     *
     * @return null|string
     */
    public function getCoreName() : ?string
    {

        return $this->name;
    }

    /**
     * Setter for `name` field value.
     *
     * @param null|string $name Value to be set.
     *
     * @return Thing
     */
    public function setName(?string $name) : Thing
    {

        return $this->setCoreName($name);
    }

    /**
     * Core setter for `name` field value.
     *
     * @param null|string $name Value to be set.
     *
     * @return Thing
     */
    public function setCoreName(?string $name) : Thing
    {

        // Test value.
        $this->grabField('name')->isValueValid($name);

        // If there is nothing to be changed.
        if ($this->name === $name) {
            return $this;
        }

        // Save.
        $this->name = $name;

        // Note that was set.
        $this->setFields[]     = 'name';
        $this->changedFields[] = 'name';

        // Note that was changed.
        if (isset($this->fieldsValuesHistory['name']) === false) {
            $this->fieldsValuesHistory['name'] = [];
        }
        $this->fieldsValuesHistory['name'][] = $name;

        return $this;
    }

    /**
     * Getter for `json_data` field value.
     *
     * @return null|stdClass
     */
    public function getJsonData() : ?stdClass
    {

        return $this->getCoreJsonData(...func_get_args());
    }

    /**
     * Core getter for `json_data` field value.
     *
     * @return null|stdClass
     */
    public function getCoreJsonData() : ?stdClass
    {

        // Convert to JSON object if needed.
        if (is_string($this->jsonData) === true) {
            $this->jsonData = json_decode($this->jsonData);
        }

        return $this->jsonData;
    }

    /**
     * Setter for `json_data` field value.
     *
     * @param null|string|stdClass $jsonData Value to be set.
     *
     * @return Thing
     */
    public function setJsonData($jsonData) : Thing
    {

        return $this->setCoreJsonData($jsonData);
    }

    /**
     * Core setter for `json_data` field value.
     *
     * @param null|string|stdClass $jsonData Value to be set.
     *
     * @return Thing
     */
    public function setCoreJsonData($jsonData) : Thing
    {

        // Test value.
        $jsonData = $this->grabField('json_data')->setProperType($jsonData);
        $this->grabField('json_data')->isValueValid($jsonData);

        // If there is nothing to be changed.
        if ($this->jsonData === $jsonData
            || json_encode($this->jsonData) === json_encode($jsonData)
        ) {
            return $this;
        }

        // Save.
        $this->jsonData = $jsonData;

        // Note that was set.
        $this->setFields[]     = 'json_data';
        $this->changedFields[] = 'json_data';

        // Note that was changed.
        if (isset($this->fieldsValuesHistory['json_data']) === false) {
            $this->fieldsValuesHistory['json_data'] = [];
        }
        $this->fieldsValuesHistory['json_data'][] = $jsonData;

        return $this;
    }
}
