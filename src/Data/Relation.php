<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Exceptions\Model\RelationFieldFromDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\RelationFieldFromIsCorrupted;
use Przeslijmi\Shortquery\Exceptions\Model\RelationFieldToDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\RelationFieldToIsCorrupted;
use Przeslijmi\Shortquery\Exceptions\Model\RelationModelFromDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\RelationModelFromIsCorrupted;
use Przeslijmi\Shortquery\Exceptions\Model\RelationModelToDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\RelationModelToIsCorrupted;
use Przeslijmi\Shortquery\Exceptions\Model\RelationNameWrosynException;
use Przeslijmi\Shortquery\Exceptions\Model\RelationFailedToCreateRule;
use Przeslijmi\Shortquery\Items\LogicAnd;
use Przeslijmi\Shortquery\Items\LogicItem;
use Przeslijmi\Shortquery\Items\Rule;
use Przeslijmi\Sivalidator\RegEx;
use Throwable;

/**
 * Defines Relation between two Models.
 */
abstract class Relation
{

    /**
     * Relation type.
     *
     * @var string
     */
    private $type;

    /**
     * Relation from which Model (by name).
     *
     * @var string
     */
    private $modelFrom;

    /**
     * Relation to which Model (by name).
     *
     * @var string
     */
    private $modelTo;

    /**
     * Name of Relation.
     *
     * @var string
     */
    private $name;

    /**
     * Name of Field from which Relations starts.
     *
     * @var string
     */
    private $fieldFrom;

    /**
     * Name of Field to which Relations leads.
     *
     * @var string
     */
    private $fieldTo;

    /**
     * Only for Creator - set of strings with PHP codes to be put in PHP model file on generation.
     *
     * Served by `->addLogicsSyntax()` and `->addLogics()`.
     *
     * @var string[]
     */
    private $logicsSyntax = [];

    /**
     * Set of rules defined for relation
     */
    private $rules = [];

    /**
     *
     */
    private $logics = [];

    /**
     * Setter for `type`.
     *
     * @param string $type Type of Relation (hasMany or hasOne).
     *
     * @since  v1.0
     * @return string
     */
    protected function setType(string $type) : self
    {

        $this->type = $type;

        return $this;
    }

    /**
     * Getter for Relation type.
     *
     * @since  v1.0
     * @return string
     */
    public function getType() : string
    {

        return $this->type;
    }

    /**
     * Setter for `name`.
     *
     * @param string $name Name of Relation.
     *
     * @since  v1.0
     * @throws RelationNameWrosynException When name is corrupted.
     * @return string
     */
    protected function setName(string $name) : self
    {

        // If name is proper.
        try {
            RegEx::ifMatches($name, '/^([a-zA-Z_])+([a-zA-Z0-9_])*$/');
        } catch (Throwable $thr) {
            throw new RelationNameWrosynException($name, $this, $thr);
        }

        $this->name = $name;

        return $this;
    }

    /**
     * Getter for Relation name.
     *
     * @since  v1.0
     * @return string
     */
    public function getName() : string
    {

        return $this->name;
    }

    /**
     * Setter for `modelFrom`.
     *
     * @param string $modelFrom Model name from which Relation starts.
     *
     * @since  v1.0
     * @return self
     */
    public function setModelFrom(string $modelFrom) : self
    {

        // Save.
        $this->modelFrom = $modelFrom;

        return $this;
    }

    /**
     * Getter for `modelFrom`.
     *
     * @since  v1.0
     * @throws RelationModelFromDonoexException When relation's ModelFrom is not defined.
     * @throws RelationModelFromIsCorrupted     When relation's ModelFrom is defined but can't be instantiated.
     * @return Model
     */
    public function getModelFrom() : Model
    {

        // Lvd.
        $instance = null;

        // Check if exists.
        if ($this->modelFrom === null) {
            throw new RelationModelFromDonoexException($this);
        }

        // Try to create instance.
        try {
            $instance = $this->modelFrom::getInstance();
        } catch (Throwable $thr) {
            throw new RelationModelFromIsCorrupted($this, $thr);
        }

        return $instance;
    }

    /**
     * Getter for `modelFrom` as a string class.
     *
     * @since  v1.0
     * @throws RelationModelFromDonoexException When relation's ModelFrom is not defined.
     * @return string
     */
    public function getModelFromAsName() : string
    {

        // Check if exists.
        if ($this->modelFrom === null) {
            throw new RelationModelFromDonoexException($this);
        }

        return $this->modelFrom;
    }

    /**
     * Setter for `modelTo`.
     *
     * @param string $modelTo Model name to which Relation leads.
     *
     * @since  v1.0
     * @return self
     */
    public function setModelTo(string $modelTo) : self
    {

        // Save.
        $this->modelTo = $modelTo;

        return $this;
    }

    /**
     * Getter for `modelTo`.
     *
     * @since  v1.0
     * @throws RelationModelToDonoexException When relation's ModelTo is not defined.
     * @throws RelationModelToIsCorrupted     When relation's ModelTo is defined but can't be instantiated.
     * @return Model
     */
    public function getModelTo() : Model
    {

        // Lvd.
        $instance = null;

        // Check if exists.
        if ($this->modelTo === null) {
            throw new RelationModelToDonoexException($this);
        }

        // Try to create instance.
        try {
            $instance = $this->modelTo::getInstance();
        } catch (Throwable $thr) {
            throw new RelationModelToIsCorrupted($this, $thr);
        }

        return $instance;
    }

    /**
     * Getter for `modelTo` as a string class.
     *
     * @since  v1.0
     * @throws RelationModelToDonoexException When relation's ModelFrom is not defined.
     * @return string
     */
    public function getModelToAsName() : string
    {

        // Check if exists.
        if ($this->modelTo === null) {
            throw new RelationModelToDonoexException($this);
        }

        return $this->modelTo;
    }

    /**
     * Setter for `fieldFrom`.
     *
     * @param string $fieldFrom Name of Field from which Relation starts.
     *
     * @since  v1.0
     * @return self
     */
    public function setFieldFrom(string $fieldFrom) : self
    {

        $this->fieldFrom = $fieldFrom;

        return $this;
    }

    /**
     * Getter for `fieldFrom`.
     *
     * @since  v1.0
     * @throws RelationFieldFromDonoexException When relation's FieldFrom is not defined.
     * @throws RelationFieldFromIsCorrupted     When relation's FieldFrom is defined but can't be instantiated.
     * @return Field
     */
    public function getFieldFrom() : Field
    {

        // Lvd.
        $field = null;

        // Check if exists.
        if ($this->fieldFrom === null) {
            throw new RelationFieldFromDonoexException($this);
        }

        // Try to create field.
        try {
            $field = $this->getModelFrom()->getFieldByName($this->fieldFrom);
        } catch (Throwable $thr) {
            throw new RelationFieldFromIsCorrupted($this, $thr);
        }

        return $field;
    }

    /**
     * Getter for `fieldFrom` as a Field name.
     *
     * @since  v1.0
     * @throws RelationFieldFromDonoexException When relation's FieldFrom is not defined.
     * @return string
     */
    public function getFieldFromAsName() : string
    {

        // Check if exists.
        if ($this->fieldFrom === null) {
            throw new RelationFieldFromDonoexException($this);
        }

        return $this->fieldFrom;
    }

    /**
     * Setter for `fieldTo`.
     *
     * @param string $fieldTo Name of Field to which Relation leads.
     *
     * @since  v1.0
     * @return self
     */
    public function setFieldTo(string $fieldTo) : self
    {

        $this->fieldTo = $fieldTo;

        return $this;
    }

    /**
     * Getter for `fieldTo`.
     *
     * @since  v1.0
     * @throws RelationFieldToDonoexException When relation's FieldTo is not defined.
     * @throws RelationFieldToIsCorrupted     When relation's FieldTo is defined but can't be instantiated.
     * @return Field
     */
    public function getFieldTo() : Field
    {

        // Lvd.
        $field = null;

        // Check if exists.
        if ($this->fieldTo === null) {
            throw new RelationFieldToDonoexException($this);
        }

        // Try to create field.
        try {
            $field = $this->getModelTo()->getFieldByName($this->fieldTo);
        } catch (Throwable $thr) {
            throw new RelationFieldToIsCorrupted($this, $thr);
        }

        return $field;
    }

    /**
     * Getter for `fieldTo` as a Field name.
     *
     * @since  v1.0
     * @throws RelationFieldToDonoexException When relation's FieldTo is not defined.
     * @return string
     */
    public function getFieldToAsName() : string
    {

        // Check if exists.
        if ($this->fieldTo === null) {
            throw new RelationFieldToDonoexException($this);
        }

        return $this->fieldTo;
    }

    /**
     * Returns name of method that expands Instance or Collection with children from Relation.
     *
     * @since  v1.0
     * @return string
     */
    public function getExpanderName() : string
    {

        return 'expand' . ucfirst($this->getName());
    }

    /**
     * Returns name of method that adds one child-Instance to Relation Collection.
     *
     * @since  v1.0
     * @return string
     */
    public function getAdderName() : string
    {

        return 'add' . ucfirst($this->getName());
    }

    /**
     * Returns name of method that returns child-Instance or child-Collection in Relation.
     *
     * For hasOne Relation child-Instance will be returned with that method.
     * For hasMany Relation child-Collection will be returned with that method.
     *
     * @since  v1.0
     * @return string
     */
    public function getGetterName() : string
    {

        return 'get' . ucfirst($this->getName());
    }

    /**
     * Adds obligatory logics to add to relation to narrow results.
     *
     * @param string $logicsSyntax Php string to copy to final models.
     *
     * @since  v1.0
     * @return self
     */
    public function addLogicsSyntax(string $logicsSyntax) : self
    {

        $this->logicsSyntax[] = $logicsSyntax;

        return $this;
    }

    /**
     * Getter for all sent logic syntax - this is used only for Creator.
     *
     * After creator Logics syntax are conferted into Rules and real Logics, accesible via
     * `$this->getLogics()` while `$this->getLogicsSyntax()` remain shut.
     *
     * @since  v1.0
     * @return array
     */
    public function getLogicsSyntax() : array
    {

        return $this->logicsSyntax;
    }

    /**
     * Adds one Rule into this Relations logics (using Rule wrapped factory).
     *
     * @since  v1.0
     * @throws RelationFailedToCreateRule When Relation failed to create Rule.
     * @return self
     */
    public function addRule() : self
    {

        // Try to create LogicItem out of this params.
        try {
            $logicItem = Rule::factoryWrapped(...func_get_args());
        } catch (Throwable $thr) {
            throw new RelationFailedToCreateRule(func_get_args(), $this);
        }

        $this->addLogic($logicItem);

        return $this;
    }

    /**
     * Adds one LogicItem into this Relations logics.
     *
     * @param LogicItem $logic One LogicItem with one or more Rules.
     *
     * @since  v1.0
     * @return self
     */
    public function addLogic(LogicItem $logic) : self
    {

        $this->logics[] = $logic;

        return $this;
    }

    /**
     * Return all logics this relation has defined.
     *
     * @since  v1.0
     * @return LogicItem[]
     */
    public function getLogics() : array
    {

        return $this->logics;
    }

    /**
     * Checks if this relation has any logics defined.
     *
     * @since  v1.0
     * @return boolean
     */
    public function hasLogics() : bool
    {

        return ( count($this->logics) > 0 );
    }
}
