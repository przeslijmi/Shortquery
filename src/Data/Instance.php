<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Exceptions\Items\FieldDictValueUnaccesibleException;
use Przeslijmi\Shortquery\Exceptions\Items\FieldValueUnaccesibleException;
use Przeslijmi\Shortquery\Items\Rule;
use Przeslijmi\Silogger\Log;
use Throwable;

/**
 * Parent for all items in all models.
 *
 * Beware! To void disambigustation get* is changed to grab*, and set* is changed to define*.
 */
abstract class Instance
{

    /**
     * In which database this instance is present.
     *
     * @var string
     */
    protected $database;

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
     * Fileds that were set during instantiation.
     *
     * @var string[]
     */
    protected $setFields = [];

    /**
     * List of changed fields.
     *
     * @var string[]
     */
    protected $changedFields = [];

    /**
     * List of changed fields.
     *
     * @var string[]
     */
    protected $fieldsValuesHistory = [];

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
     * @return Field
     */
    public function grabField(string $fieldName) : Field
    {

        return $this->grabModel()->getFieldByName($fieldName);
    }

    /**
     * Getter for Field value.
     *
     * @param string $fieldName Name of Field.
     *
     * @throws FieldValueUnaccesibleException When field is not present.
     * @return mixed
     */
    public function grabFieldValue(string $fieldName)
    {

        // Lvd.
        $result = null;

        // Work.
        try {
            $getter = $this->grabField($fieldName)->getGetterName();
            $result = $this->$getter();
        } catch (Throwable $thr) {
            throw new FieldValueUnaccesibleException([ get_class($this), $fieldName ], 0, $thr);
        }

        return $result;
    }

    /**
     * Getter for field dictionary one value (for set end enum fields).
     *
     * @param string      $fieldName Name of field.
     * @param string      $dictName  Dictionary name (use `main` as standard).
     * @param null|string $value     Dictionary value.
     *
     * @throws FieldDictValueUnaccesibleException When it is impossible to get this field's dict value.
     * @return string
     */
    public function grabDictFieldValue(string $fieldName, string $dictName, ?string $value) : string
    {

        // Short track.
        if (empty($value) === true) {
            return '';
        }

        // Lvd.
        $result = '';

        // Work.
        try {
            $result = $this->grabField($fieldName)->getDictValue($value, $dictName);
        } catch (Throwable $thr) {
            throw new FieldDictValueUnaccesibleException(
                [ get_class($this), $fieldName, $dictName, $value ],
                0,
                $thr
            );
        }

        return $result;
    }

    /**
     * Getter for field dictionary many values (sent as comma separated list) (for set end enum fields).
     *
     * @param string      $fieldName Name of field.
     * @param string      $dictName  Dictionary name (use `main` as standard).
     * @param null|string $value     Dictionary values separated with comma.
     *
     * @throws FieldDictValueUnaccesibleException When it is impossible to get this field's dict value.
     * @return string
     */
    public function grabMultiDictFieldValue(string $fieldName, string $dictName, ?string $value) : string
    {

        // Shortcut.
        if (empty($value) === true) {
            return '';
        }

        // Lvd.
        $result  = '';
        $results = [];

        // Work.
        try {

            // Get field.
            $field = $this->grabField($fieldName);

            // Fill up values.
            foreach (explode(',', $value) as $oneValue) {
                $results[] = str_replace(',', '\,', $field->getDictValue($oneValue, $dictName));
            }

            // Impolode.
            $result = implode(',', $results);
        } catch (Throwable $thr) {
            throw new FieldDictValueUnaccesibleException(
                [ get_class($this), $fieldName, $dictName, $value ],
                0,
                $thr
            );
        }

        return $result;
    }

    /**
     * Setter for if this Instance is already in Engine.
     *
     * @param boolean $isAdded If this Instance is already in Engine.
     *
     * @return self
     */
    public function defineIsAdded(bool $isAdded) : self
    {

        if ($isAdded === false) {
            $this->resetPrimaryKey();
        }

        $this->isAdded = $isAdded;

        return $this;
    }

    /**
     * Getter for if this Instance is already in Engine.
     *
     * @return boolean
     */
    public function grabIsAdded() : bool
    {

        return $this->isAdded;
    }

    /**
     * Setter for if this Instance in Collection is to be deleted.
     *
     * @param boolean $isToBeDeleted If this Instance in Collection is to be deleted.
     *
     * @return self
     */
    public function defineIsToBeDeleted(bool $isToBeDeleted) : self
    {

        $this->isToBeDeleted = $isToBeDeleted;

        return $this;
    }

    /**
     * Getter for if this Instance inside Collection is decided to be deleted.
     *
     * @return boolean
     */
    public function grabIsToBeDeleted() : bool
    {

        return $this->isToBeDeleted;
    }

    /**
     * Setter to add info that there were no changes in this instance (so `save()` will ignore).
     *
     * @return self
     */
    public function defineNothingChanged() : self
    {

        // Make array with changed fields empty.
        $this->changedFields = [];

        return $this;
    }

    /**
     * Return info if any field in this Instance has been changed.
     *
     * @return boolean
     */
    public function grabHaveAnythingChanged() : bool
    {

        return ( count($this->changedFields) > 0 );
    }

    /**
     * Return info if primary key field in this Instance has been changed.
     *
     * @return boolean
     */
    public function grabPkFieldHasChanged() : bool
    {

        return in_array($this->grabPkName(), $this->grabChangedFieldsNames());
    }

    /**
     * Return list of fields that has been changed in this Instance.
     *
     * @return string[]
     */
    public function grabChangedFieldsNames() : array
    {

        return $this->changedFields;
    }

    /**
     * Getter for Primary Key field.
     *
     * @return Field
     */
    public function grabPkField() : Field
    {

        return $this->grabModel()->getPkField();
    }

    /**
     * Getter for Primary Key name.
     *
     * @return string
     */
    public function grabPkName() : string
    {

        return $this->grabModel()->getPkField()->getName();
    }

    /**
     * Getter for Primary Key value.
     *
     * @return mixed
     */
    public function grabPkValue()
    {

        $pkGetter = $this->grabModel()->getPkField()->getGetterName();

        return $this->$pkGetter();
    }

    /**
     * Getter for Primary Key previous value (if changed).
     *
     * @return mixed
     */
    public function grabPkPreviousValue()
    {

        return $this->fieldsValuesHistory[$this->grabPkName()][0];
    }

    /**
     * Setter for Primary Key value.
     *
     * @param mixed $value Primary key value.
     *
     * @return mixed
     */
    public function definePkValue($value)
    {

        $pkSetter = $this->grabModel()->getPkField()->getSetterName();

        return $this->$pkSetter($value);
    }

    /**
     * Read into this Instance current values of fields from Engine.
     *
     * @return self
     */
    public function read() : self
    {

        // Create SELECT query.
        $select = $this->grabModel()->newSelect($this->database);

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
     * @param boolean $onlyReturnQuery Opt., `false`. If set to true query will be sent to debug log without executing.
     *
     * @return self
     */
    public function create(bool $onlyReturnQuery = false) : self
    {

        // Create INSERT query.
        $insert = $this->grabModel()->newInsert();

        // Add logics.
        $insert->setInstance($this);

        // Debug.
        if ($onlyReturnQuery === true) {
            if (empty($insert->toString()) === false) {
                Log::get()->debug($insert->toString());
            }
            return $this;
        }

        // Fire query.
        $insert->fire();

        // Set primary key - if there is no.
        if ($this->hasPrimaryKey() === false) {
            $pkSetter = $this->grabModel()->getPrimaryKeyField()->getSetterName();
            $this->$pkSetter($insert->getAddedPk());
        }

        return $this;
    }

    /**
     * Deliver create query.
     *
     * @return string
     */
    public function createQuery() : string
    {

        // Create INSERT query.
        $insert = $this->grabModel()->newInsert();

        // Add logics.
        $insert->setInstance($this);

        return $insert->toString();
    }

    /**
     * Create record if it not exists - otherwise try to read.
     *
     * @return self
     */
    public function createIfNotExists() : self
    {

        // Try to read.
        $this->read();

        // This is call to Entity own method. If PK is defined in this record after reading
        // than yeah - this record exists.
        if ($this->hasPrimaryKey() === false) {
            $this->create();
        }

        return $this;
    }

    /**
     * Update existing record.
     *
     * @param boolean $onlyReturnQuery Opt., `false`. If set to true query will be sent to debug log without executing.
     *
     * @return self
     */
    public function update(bool $onlyReturnQuery = false) : self
    {

        // Create UPDATE query.
        $update = $this->grabModel()->newUpdate();

        // Add logics.
        $update->setLogicsSet([ Rule::factoryWrapped($this->grabPkName(), $this->grabPkValue()) ]);

        // Add this Instance.
        $update->setInstance($this);

        // Debug.
        if ($onlyReturnQuery === true) {
            if (empty($update->toString()) === false) {
                Log::get()->debug($update->toString());
            }
            return $this;
        }

        // Fire query.
        $update->fire();

        return $this;
    }

    /**
     * Deliver update query.
     *
     * @return string
     */
    public function updateQuery() : string
    {

        // Create UPDATE query.
        $update = $this->grabModel()->newUpdate();

        // Add logics.
        $update->setLogicsSet([ Rule::factoryWrapped($this->grabPkName(), $this->grabPkValue()) ]);

        // Add logics.
        $update->setInstance($this);

        return $update->toString();
    }

    /**
     * Save record (no matter if update or creation are needed)..
     *
     * @param boolean $onlyReturnQuery Opt., `false`. If set to true query will be sent to debug log without executing.
     *
     * @return self
     */
    public function save(bool $onlyReturnQuery = false) : self
    {

        // If this is already added in the Engine - then update.
        if ($this->grabIsAdded() === true) {
            return $this->update($onlyReturnQuery);
        }

        return $this->create($onlyReturnQuery);
    }

    /**
     * Deliver create or update query.
     *
     * @return string
     */
    public function saveQuery() : string
    {

        // If this is already added in the Engine - then update.
        if ($this->grabIsAdded() === true) {
            return $this->updateQuery();
        }

        return $this->createQuery();
    }

    /**
     * Save record (no matter if update or creation are needed)..
     *
     * @param boolean $onlyReturnQuery Opt., `false`. If set to true query will be sent to debug log without executing.
     *
     * @return self
     */
    public function delete(bool $onlyReturnQuery = false) : self
    {

        // If this is not added - it can't be deleted.
        if ($this->grabIsAdded() === false) {
            return $this;
        }

        // Create DELETE query.
        $delete = $this->grabModel()->newDelete();

        // Add logics.
        $delete->setLogicsSet([ Rule::factoryWrapped($this->grabPkName(), $this->grabPkValue()) ]);

        // Add this Instance.
        $delete->setInstance($this);

        // Debug.
        if ($onlyReturnQuery === true) {
            if (empty($delete->toString()) === false) {
                Log::get()->debug($delete->toString());
            }
            return $this;
        }

        // Fire query.
        $delete->fire();

        // Set that this record is not added.
        $this->defineIsAdded(false);

        return $this;
    }

    /**
     * Converts this item into string - showing all fields and its values.
     *
     * @return string
     */
    public function toString() : string
    {

        // Lvd.
        $result = '';

        // Go thru all fields.
        foreach ($this->setFields as $fieldName) {

            // Lvd.
            $field      = $this->grabField($fieldName);
            $getterName = $field->getGetterName();
            $value      = ( $this->$getterName() ?? 'NULL' );

            // Compose.
            $result .= $field->getName() . ': ' . $value . "\n";
        }

        return $result;
    }
}
