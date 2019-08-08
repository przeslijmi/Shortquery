<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Items\Rule;
use Przeslijmi\Shortquery\Items\LogicItem;
use Przeslijmi\Shortquery\Items\LogicAnd;

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
     *
     */
    private $rules = [];

    /**
     *
     */
    private $logics = [];

    /**
     *
     */
    private $logicsSyntax = [];

    /**
     * Setter for `type`.
     *
     * @param string $type Type of Relation (hasMany or hasOne).
     *
     * @since  v1.0
     * @return string
     */
    public function setType(string $type) : self
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
     * @return string
     */
    public function setName(string $name) : self
    {

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
     * @return Model
     */
    public function getModelFrom() : Model
    {

        return $this->modelFrom::getInstance();
    }

    /**
     * Getter for `modelFrom` as a string class.
     *
     * @since  v1.0
     * @return string
     */
    public function getModelFromAsName() : string
    {

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
     * @return Model
     */
    public function getModelTo() : Model
    {

        return $this->modelTo::getInstance();
    }

    /**
     * Getter for `modelTo` as a string class.
     *
     * @since  v1.0
     * @return string
     */
    public function getModelToAsName() : string
    {

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
     * @return Field
     */
    public function getFieldFrom() : Field
    {

        return $this->getModelFrom()->getFieldByName($this->fieldFrom);
    }

    /**
     * Getter for `fieldFrom` as a Field name.
     *
     * @since  v1.0
     * @return string
     */
    public function getFieldFromAsName() : string
    {

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
     * @return Field
     */
    public function getFieldTo() : Field
    {

        return $this->getModelTo()->getFieldByName($this->fieldTo);
    }

    /**
     * Getter for `fieldTo` as a Field name.
     *
     * @since  v1.0
     * @return string
     */
    public function getFieldToAsName() : string
    {

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

    public function addLogicsSyntax(string $logicsSyntax) : self
    {

        $this->logicsSyntax[] = $logicsSyntax;

        return $this;
    }

    public function getSyntax() : array
    {

        return $this->logicsSyntax;
    }

    public function addLogics(LogicItem $logic) : self
    {

        $this->logics[] = $logic;
    }

    public function addRule() : self
    {

        try {
            $rule = Rule::factory(...func_get_args());
        } catch (Exception $e) {
            throw ( new MethodFopException('creationOfRuleFailed', $e) )
                ->addInfos(func_get_args(), 'ruleArgs');
        }

        $this->rules[] = $rule;

        return $this;
    }

    public function getLogics() : array
    {

        $logics = [];

        if (count($this->rules) > 0) {
            $logics[] = new LogicAnd(...$this->rules);
        }

        return $logics;
    }

    public function hasLogics() : bool
    {

        return ( count($this->rules) > 0 || count($this->logics) > 0 || count($this->logicsSyntax) > 0 );
    }
}
