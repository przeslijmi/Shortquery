<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\ForTests\Models\Core;

use Przeslijmi\Shortquery\Data\Instance;
use Przeslijmi\Shortquery\Exceptions\Data\CollectionSliceNotPossibleException;
use Przeslijmi\Shortquery\ForTests\Models\Car;
use Przeslijmi\Shortquery\ForTests\Models\Cars;
use Przeslijmi\Shortquery\ForTests\Models\Core\CarModel;
use Przeslijmi\Shortquery\ForTests\Models\Girl;
use Przeslijmi\Shortquery\ForTests\Models\Girls;
use Przeslijmi\Shortquery\Tools\InstancesFactory;
use stdClass;

/**
 * ShortQuery Core class for Car Model.
 */
class CarCore extends Instance
{

    /**
     * Field `pk`.
     *
     * @var integer
     */
    private $pk;

    /**
     * Field `owner_girl`.
     *
     * @var null|integer
     */
    private $ownerGirl;

    /**
     * Field `is_fast`.
     *
     * @var null|string
     */
    private $isFast;

    /**
     * Field `name`.
     *
     * @var null|string
     */
    private $name;

    /**
     * Relation `oneOwnerGirl`.
     *
     * @var Girls
     */
    private $oneOwnerGirl;

    /**
     * Relation `oneOwnerGirlWithSnapchat`.
     *
     * @var Girls
     */
    private $oneOwnerGirlWithSnapchat;

    /**
     * Constructor.
     */
    public function __construct(?string $database = null)
    {

        // Get model Instance.
        $this->model = CarModel::getInstance();

        // Set database if given.
        $this->database = $database;
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

        if (is_int($noInSet)) {
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
     * @return Car
     */
    public function setPk(int $pk) : Car
    {

        return $this->setCorePk($pk);
    }

    /**
     * Core setter for `pk` field value.
     *
     * @param integer $pk Value to be set.
     *
     * @return Car
     */
    public function setCorePk(int $pk) : Car
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

        return $this;
    }

    /**
     * Getter for `owner_girl` field value.
     *
     * @return null|integer
     */
    public function getOwnerGirl() : ?int
    {

        return $this->getCoreOwnerGirl(...func_get_args());
    }

    /**
     * Core getter for `owner_girl` field value.
     *
     * @return null|integer
     */
    public function getCoreOwnerGirl() : ?int
    {

        return $this->ownerGirl;
    }


    /**
     * Setter for `owner_girl` field value.
     *
     * @param null|integer $ownerGirl Value to be set.
     *
     * @return Car
     */
    public function setOwnerGirl(?int $ownerGirl) : Car
    {

        return $this->setCoreOwnerGirl($ownerGirl);
    }

    /**
     * Core setter for `owner_girl` field value.
     *
     * @param null|integer $ownerGirl Value to be set.
     *
     * @return Car
     */
    public function setCoreOwnerGirl(?int $ownerGirl) : Car
    {

        // Test value.
        $this->grabField('owner_girl')->isValueValid($ownerGirl);

        // If there is nothing to be changed.
        if ($this->ownerGirl === $ownerGirl) {
            return $this;
        }

        // Save.
        $this->ownerGirl = $ownerGirl;

        // Note that was set.
        $this->setFields[]     = 'owner_girl';
        $this->changedFields[] = 'owner_girl';

        return $this;
    }

    /**
     * Getter for `is_fast` field value.
     *
     * @return null|string
     */
    public function getIsFast() : ?string
    {

        return $this->getCoreIsFast(...func_get_args());
    }

    /**
     * Core getter for `is_fast` field value.
     *
     * @return null|string
     */
    public function getCoreIsFast() : ?string
    {

        if (func_num_args() === 0) {
            return $this->isFast;
        }

        return $this->grabDictFieldValue('is_fast', ( func_get_args()[0] ?? 'main' ), $this->isFast);
    }


    /**
     * Setter for `is_fast` field value.
     *
     * @param null|string $isFast Value to be set.
     *
     * @return Car
     */
    public function setIsFast(?string $isFast) : Car
    {

        return $this->setCoreIsFast($isFast);
    }

    /**
     * Core setter for `is_fast` field value.
     *
     * @param null|string $isFast Value to be set.
     *
     * @return Car
     */
    public function setCoreIsFast(?string $isFast) : Car
    {

        // Test value.
        $this->grabField('is_fast')->isValueValid($isFast);

        // If there is nothing to be changed.
        if ($this->isFast === $isFast) {
            return $this;
        }

        // Save.
        $this->isFast = $isFast;

        // Note that was set.
        $this->setFields[]     = 'is_fast';
        $this->changedFields[] = 'is_fast';

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
     * @return Car
     */
    public function setName(?string $name) : Car
    {

        return $this->setCoreName($name);
    }

    /**
     * Core setter for `name` field value.
     *
     * @param null|string $name Value to be set.
     *
     * @return Car
     */
    public function setCoreName(?string $name) : Car
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

        return $this;
    }

    /**
     * Returns child-Instance (one and only - for hasOne Relation type) in Relation.
     *
     * @return Girl
     */
    public function getOneOwnerGirl() : Girl
    {

        return $this->oneOwnerGirl->getOne();
    }

    /**
     * Call to add children (Girl) to this Instance.
     *
     * @since  v1.0
     * @return self
     */
    public function expandOneOwnerGirl() : self
    {

        // Get records with those pks.
        $child = new Girl(...func_get_args());

        // If we know that we need this one - read this one.
        if ($this->getOwnerGirl() !== null) {
            $child->setPk($this->getOwnerGirl());
            $child->read();
        }

        // Add this child (empty or not).
        $this->addOneOwnerGirl($child);

        return $this;
    }

    /**
     * Adds one child-Instance to Relation Collection.
     *
     * @param Girl $instance One child-Instance of child for Relation.
     *
     * @return self
     */
    public function addOneOwnerGirl(Girl $instance) : self
    {

        // If there is no Collection created - create one.
        if (is_null($this->oneOwnerGirl) === true) {
            $this->oneOwnerGirl = new Girls();
        }

        // Put this Instance to this Collection.
        $this->oneOwnerGirl->put($instance);

        return $this;
    }

    /**
     * Returns child-Instance (one and only - for hasOne Relation type) in Relation.
     *
     * @return Girl
     */
    public function getOneOwnerGirlWithSnapchat() : Girl
    {

        return $this->oneOwnerGirlWithSnapchat->getOne();
    }

    /**
     * Call to add children (Girl) to this Instance.
     *
     * @since  v1.0
     * @return self
     */
    public function expandOneOwnerGirlWithSnapchat() : self
    {

        // Get records with those pks.
        $child = new Girl(...func_get_args());

        // If we know that we need this one - read this one.
        if ($this->getOwnerGirl() !== null) {
            $child->setPk($this->getOwnerGirl());
            $child->read();
        }

        // Add this child (empty or not).
        $this->addOneOwnerGirlWithSnapchat($child);

        return $this;
    }

    /**
     * Adds one child-Instance to Relation Collection.
     *
     * @param Girl $instance One child-Instance of child for Relation.
     *
     * @return self
     */
    public function addOneOwnerGirlWithSnapchat(Girl $instance) : self
    {

        // If there is no Collection created - create one.
        if (is_null($this->oneOwnerGirlWithSnapchat) === true) {
            $this->oneOwnerGirlWithSnapchat = new Girls();
        }

        // Put this Instance to this Collection.
        $this->oneOwnerGirlWithSnapchat->put($instance);

        return $this;
    }
}