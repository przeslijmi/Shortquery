<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\ForTests\Models\Core;

use Przeslijmi\Shortquery\Data\Instance;
use Przeslijmi\Shortquery\Exceptions\Data\CollectionSliceNotPossibleException;
use Przeslijmi\Shortquery\ForTests\Models\Car as Car9774;
use Przeslijmi\Shortquery\ForTests\Models\Cars as Cars9794;
use Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel;
use Przeslijmi\Shortquery\ForTests\Models\Girl;
use Przeslijmi\Shortquery\ForTests\Models\Girls;
use Przeslijmi\Shortquery\Tools\InstancesFactory;
use stdClass;

/**
 * ShortQuery Core class for Girl Model.
 */
class GirlCore extends Instance
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
     * Field `webs`.
     *
     * @var null|string
     */
    private $webs;

    /**
     * Relation `cars`.
     *
     * @var Cars
     */
    private $cars;

    /**
     * Relation `fastCars`.
     *
     * @var Cars
     */
    private $fastCars;

    /**
     * Constructor.
     *
     * @param string $database Optional, `null`. In which database this field is defined.
     */
    public function __construct(?string $database = null)
    {

        // Get model Instance.
        $this->model = GirlModel::getInstance();

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
        if (isset($inject['webs']) === true && $inject['webs'] !== null) {
            $this->webs = (string) $inject['webs'];
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
     * @return Girl
     */
    public function setPk(int $pk) : Girl
    {

        return $this->setCorePk($pk);
    }

    /**
     * Core setter for `pk` field value.
     *
     * @param integer $pk Value to be set.
     *
     * @return Girl
     */
    public function setCorePk(int $pk) : Girl
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
     * @return Girl
     */
    public function setName(?string $name) : Girl
    {

        return $this->setCoreName($name);
    }

    /**
     * Core setter for `name` field value.
     *
     * @param null|string $name Value to be set.
     *
     * @return Girl
     */
    public function setCoreName(?string $name) : Girl
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
     * Getter for `webs` field value.
     *
     * @return null|string
     */
    public function getWebs() : ?string
    {

        return $this->getCoreWebs(...func_get_args());
    }

    /**
     * Core getter for `webs` field value.
     *
     * @return null|string
     */
    public function getCoreWebs() : ?string
    {

        if (func_num_args() === 0) {
            return $this->webs;
        }

        return $this->grabMultiDictFieldValue('webs', ( func_get_args()[0] ?? 'main' ), $this->webs);
    }

    /**
     * Adds given value to set.
     *
     * @param string $toBeAdded String value to be added.
     *
     * @return Girl
     */
    public function addToWebs(string $toBeAdded) : Girl
    {

        if (empty($this->getWebs()) === true) {
            $value = explode(',', $toBeAdded);
        } else {
            $value = array_merge(
                explode(',', $toBeAdded),
                explode(',', $this->getWebs())
            );
        }

        $value = array_unique($value);

        $value = implode(',', $value);

        return $this->setWebs(( empty($value) === true ) ? null : $value);
    }

    /**
     * Deletes given value from set.
     *
     * @param string $toBeDeleted String value to be deleted.
     *
     * @return Girl
     */
    public function deleteFromWebs(string $toBeDeleted) : Girl
    {

        $value = explode(',', $this->getWebs());

        foreach (explode(',', $toBeDeleted) as $toDelete) {

            $is = array_search($toDelete, $value);

            if ($is !== false) {
                unset($value[$is]);
            }
        }

        $value = implode(',', $value);

        return $this->setWebs(( empty($value) === true ) ? null : $value);
    }


    /**
     * Setter for `webs` field value.
     *
     * @param null|string $webs Value to be set.
     *
     * @return Girl
     */
    public function setWebs(?string $webs) : Girl
    {

        return $this->setCoreWebs($webs);
    }

    /**
     * Core setter for `webs` field value.
     *
     * @param null|string $webs Value to be set.
     *
     * @return Girl
     */
    public function setCoreWebs(?string $webs) : Girl
    {

        // Test value.
        $this->grabField('webs')->isValueValid($webs);

        // If there is nothing to be changed.
        if (is_null($webs) === is_null($this->webs)
            && count(array_diff((array) $webs, (array) $this->webs)) === 0
            && count(array_diff((array) $this->webs, (array) $webs)) === 0
        ) {
            return $this;
        }

        // Save.
        $this->webs = $webs;

        // Note that was set.
        $this->setFields[]     = 'webs';
        $this->changedFields[] = 'webs';

        // Note that was changed.
        if (isset($this->fieldsValuesHistory['webs']) === false) {
            $this->fieldsValuesHistory['webs'] = [];
        }
        $this->fieldsValuesHistory['webs'][] = $webs;

        return $this;
    }

    /**
     * Returns child-Collection (zero or more children - for hasMany Relation type) in Relation.
     *
     * @return Cars
     */
    public function getCars() : Cars9794
    {

        // Create empty collection if there isn't any added.
        if ($this->cars === null) {
            $this->cars = new Cars9794();
            $this->cars->getLogics()->addFromRelation($this->grabModel()->getRelationByName('cars'));
        }

        return $this->cars;
    }

    /**
     * Call to add children (Cars) to this Instance.
     *
     * @since  v1.0
     * @return self
     */
    public function expandCars() : self
    {

        // Get records with those pks.
        $children = new Cars9794(...func_get_args());

        // If we know that we need this one - read this one.
        if ($this->getPk() !== null) {
            $children->getLogics()->addRule(
                'owner_girl',
                $this->getPk()
            );
            $children->getLogics()->addFromRelation($this->grabModel()->getRelationByName('cars'));
            $children->read();
        }

        // Add this child (empty or not).
        $this->addCars($children);

        return $this;
    }

    /**
     * Adds child-Collection to Relation Collection.
     *
     * @param Cars9794 $collection One child-Instance of child for Relation.
     *
     * @return self
     */
    public function addCars(Cars9794 $collection) : self
    {

        // Put this Instance to this Collection.
        $this->cars = $collection;

        return $this;
    }

    /**
     * Returns child-Collection (zero or more children - for hasMany Relation type) in Relation.
     *
     * @return Cars
     */
    public function getFastCars() : Cars9794
    {

        // Create empty collection if there isn't any added.
        if ($this->fastCars === null) {
            $this->fastCars = new Cars9794();
            $this->fastCars->getLogics()->addFromRelation($this->grabModel()->getRelationByName('fastCars'));
        }

        return $this->fastCars;
    }

    /**
     * Call to add children (Cars) to this Instance.
     *
     * @since  v1.0
     * @return self
     */
    public function expandFastCars() : self
    {

        // Get records with those pks.
        $children = new Cars9794(...func_get_args());

        // If we know that we need this one - read this one.
        if ($this->getPk() !== null) {
            $children->getLogics()->addRule(
                'owner_girl',
                $this->getPk()
            );
            $children->getLogics()->addFromRelation($this->grabModel()->getRelationByName('fastCars'));
            $children->read();
        }

        // Add this child (empty or not).
        $this->addFastCars($children);

        return $this;
    }

    /**
     * Adds child-Collection to Relation Collection.
     *
     * @param Cars9794 $collection One child-Instance of child for Relation.
     *
     * @return self
     */
    public function addFastCars(Cars9794 $collection) : self
    {

        // Put this Instance to this Collection.
        $this->fastCars = $collection;

        return $this;
    }
}
