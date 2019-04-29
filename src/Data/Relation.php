<?php

namespace Przeslijmi\Shortquery\Data;

use Przeslijmi\Shortquery\Data\Collection\Model;

/**
 * Defines relation between two Collections.
 */
class Relation
{

    /**
     * Relation from which model.
     *
     * @var Model
     */
    private $model;

    /**
     * Name of relation.
     *
     * @var string
     */
    private $name;

    /**
     * Name of Collection Class (not Core) from which relation starts (unused).
     *
     * @var string
     */
    private $collectionNameFrom;

    /**
     * Name of the collection to which relation leads.
     *
     * @var string
     */
    private $collectionNameTo;

    /**
     * Name of the field from which relations starts.
     *
     * @var string
     */
    private $fieldNameFrom;

    /**
     * Name of the field to which relations leads.
     *
     * @var string
     */
    private $fieldNameTo;

    /**
     * Constructor.
     *
     * @param string $name Name of relation.
     *
     * @since v1.0
     */
    public function __construct(string $name)
    {

        $this->name = $name;
    }

    /**
     * Setter for `$model`.
     *
     * @param Model $model Model object from which relation starts.
     *
     * @since  v1.0
     * @return void
     */
    public function setModel(Model $model) : void
    {

        $this->model = $model;
    }

    /**
     * Setter for `$collectionNameTo`.
     *
     * @param string $collectionNameTo Name of the destinaction collection class.
     *
     * @since  v1.0
     * @return self
     */
    public function setTo(string $collectionNameTo) : self
    {

        $this->collectionNameTo = $collectionNameTo;

        return $this;
    }

    /**
     * Setter for `$fieldNameFrom`.
     *
     * @param string $fieldNameFrom Name of the field from which relation starts.
     *
     * @since  v1.0
     * @return self
     */
    public function setFieldFrom(string $fieldNameFrom) : self
    {

        $this->fieldNameFrom = $fieldNameFrom;

        return $this;
    }

    /**
     * Getter for source field.
     *
     * @since  v1.0
     * @return Field
     */
    public function getFieldFrom() : Field
    {

        return $this->model->getFieldByName($this->fieldNameFrom);
    }

    /**
     * Getter for destination field.
     *
     * @since  v1.0
     * @return Field
     */
    public function getFieldTo() : Field
    {

        $collectionNameTo = $this->collectionNameTo;

        return ( new $collectionNameTo() )->getModel()->getFieldByName($this->fieldNameTo);
    }

    /**
     * Setter for `$fieldNameTo`.
     *
     * @param string $fieldNameTo Name of the field to which relation leads.
     *
     * @since  v1.0
     * @return self
     */
    public function setFieldTo(string $fieldNameTo) : self
    {

        $this->fieldNameTo = $fieldNameTo;

        return $this;
    }

    /**
     * Getter for relation name.
     *
     * @since  v1.0
     * @return string
     */
    public function getName() : string
    {

        return $this->name;
    }

    /**
     * Get name of adder method.
     *
     * @todo   Explain it.
     * @since  v1.0
     * @return string
     */
    public function getAdderMethodName() : string
    {

        return 'add' . ucfirst($this->name);
    }
}
