<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Items\Rule;

/**
 * Parent for all items in all models.
 *
 * Beware! To void disambigustation get* is changed to grab*, and set* is changed to define*.
 */
abstract class Instance
{

    /**
     * If this Instance is already in Engine.
     *
     * @var boolean
     */
    private $isAdded = false;

    /**
     * If this Instance is to be deleted in Engine.
     *
     * @var boolean
     */
    private $isToBeDeleted = false;

    /**
     *
     */
    protected $setFields = [];

    /**
     *
     */
    protected $changedFields = [];

    /**
     * Link to Model of which this Instance is.
     *
     * @var Model
     */
    protected $model;

    /**
     * Getter for Model.
     *
     * @return Model
     */
    public function grabModel() : Model
    {

        return $this->model;
    }

    /**
     * Getter for Field in Model.
     *
     * @param string $fieldName Name of field.
     *
     * @since  v1.0
     * @return Field
     */
    public function grabField(string $fieldName) : Field
    {

        return $this->grabModel()->getFieldByName($fieldName);
    }

    public function grabFieldValue(string $fieldName)
    {

        $getter = $this->grabField($fieldName)->getGetterName();

        return $this->$getter();
    }

    public function grabDictFieldValue(string $fieldName, string $dictName, string $value) : string
    {

        $field = $this->grabField($fieldName);

        return $field->getDictValue($value, $dictName);
    }

    public function grabMultiDictFieldValue(string $fieldName, string $dictName, string $value) : string
    {

        $field = $this->grabField($fieldName);

        $result = [];

        foreach (explode(',', $value) as $oneValue) {
            $result[] = str_replace(',', '\,', $field->getDictValue($oneValue, $dictName));
        }

        return implode(',', $result);
    }

    /**
     * Setter for if this Instance is already in Engine.
     *
     * @param boolean $isAdded If this Instance is already in Engine.
     *
     * @since  v1.0
     * @return self
     */
    public function defineIsAdded(bool $isAdded) : self
    {

        $this->isAdded = $isAdded;

        return $this;
    }

    /**
     * Getter for if this Instance is already in Engine.
     *
     * @since  v1.0
     * @return boolean
     */
    public function grabIsAdded() : bool
    {

        return $this->isAdded;
    }

    /**
     * Setter for if this Instance is to be deleted in Engine.
     *
     * @param boolean $isToBeDeleted If this Instance is to be deleted in Engine.
     *
     * @since  v1.0
     * @return self
     */
    public function defineIsToBeDeleted(bool $isToBeDeleted) : self
    {

        $this->isToBeDeleted = $isToBeDeleted;

        return $this;
    }

    /**
     * Getter for if this Instance is already in Engine.
     *
     * @since  v1.0
     * @return boolean
     */
    public function grabIsToBeDeleted() : bool
    {

        return $this->isToBeDeleted;
    }

    public function defineNothingChanged() : self
    {

        $this->changedFields = [];

        return $this;
    }

    public function grabHaveAnythingChanged() : bool
    {

        return ( count($this->changedFields) > 0 );
    }

    /**
     * Getter for Primary Key name.
     *
     * @since  v1.0
     * @return string
     */
    public function grabPkName() : string
    {

        return $this->grabModel()->getPkField()->getName();
    }

    /**
     * Getter for Primary Key value.
     *
     * @since  v1.0
     * @return mixed
     */
    public function grabPkValue()
    {

        $pkGetter = $this->grabModel()->getPkField()->getGetterName();

        return $this->$pkGetter();
    }

    /**
     * Read into this Instance current values of fields from Engine.
     *
     * @since  v1.0
     * @return self
     */
    public function read() : self
    {

        // Create SELECT query.
        $select = $this->grabModel()->newSelect();

        // Find logics.
        $logics = [];
        if ($this->hasPrimaryKey() === true) {
            $logics[] = Rule::factoryWrapped($this->grabPkName(), $this->grabPkValue());
        } else {
            foreach ($this->grabModel()->getFields() as $field) {
                if (in_array($field->getName(), $this->setFields) === true) {
                    $getterName = $field->getGetterName();
                    $logics[]   = Rule::factoryWrapped($field->getName(), $this->$getterName());
                }
            }
        }

        // Add logics.
        $select->setLogicsSet($logics);

        // Make reading.
        $select->readIntoInstance($this);

        return $this;
    }

    /**
     * Create record.
     *
     * @return self
     */
    public function create() : self
    {

        // Create INSERT query.
        $insert = $this->grabModel()->newInsert();

        // Add logics.
        $insert->setInstance($this);

        // Fire query.
        $insert->fire();

        // Set primary key - if there is no.
        if ($this->hasPrimaryKey() === false) {
            $pkSetter = $this->grabModel()->getPrimaryKeyField()->getSetterName();
            $this->$pkSetter($insert->getAddedPk());
        }

        return $this;
    }

    public function createIfNotExists() : self
    {

        $this->read();

        if ($this->hasPrimaryKey() === false) {
            $this->create();
        }

        return $this;
    }

    /**
     * Update existing record.
     *
     * @since  v1.0
     * @return self
     */
    public function update() : self
    {

        // Create UPDATE query.
        $update = $this->grabModel()->newUpdate();

        // Add logics.
        $update->setLogicsSet([ Rule::factoryWrapped($this->grabPkName(), $this->grabPkValue()) ]);

        // Add this Instance.
        $update->setInstance($this);

        // Fire query.
        $update->fire();

        return $this;
    }

    /**
     * Save record (no matter if update or creation are needed)..
     *
     * @since  v1.0
     * @return self
     */
    public function save() : self
    {

        // If this is already added in the Engine - then update.
        if ($this->grabIsAdded() === true) {
            return $this->update();
        }

        return $this->create();
    }

    public function toString() : string
    {

        $result = '';

        foreach ($this->grabModel()->getFields() as $field) {

            $getterName = $field->getGetterName();
            $value      = $this->$getterName();

            if ($value === null) {
                $value = 'NULL';
            }

            $result .= $field->getName() . ': ' . $value . PHP_EOL;
        }

        return $result;
    }
}
