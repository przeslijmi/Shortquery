
namespace <?= $this->model->getClass('namespaceCore') ?>;

<?php
$this->addUse('stdClass');
$this->addUse('Przeslijmi\\Shortquery\\Data\\Instance');
$this->addUse('Przeslijmi\\Shortquery\\Tools\\InstancesFactory');
$this->addUse($this->model->getClass('modelClass'));
$this->addUse($this->model->getClass('namespace') . '\\' . $this->model->getClass('instanceClassName'));
$this->addUse($this->model->getClass('namespace') . '\\' . $this->model->getClass('collectionClassName'));
foreach ($this->model->getRelations() as $relation):
    $this->addUse($relation->getModelTo()->getClass('collectionClass'));
    $this->addUse($relation->getModelTo()->getClass('instanceClass'));
endforeach;
$this->addUse('Przeslijmi\\Shortquery\\Exceptions\\Data\\CollectionSliceNotPossibleException');
$this->showNamespaces();
?>

/**
 * ShortQuery Instance Core class for <?= $this->model->getClass('instanceClassName') ?> Model.
 *
 * This is a `<shortquery-role:instance-core>`.
 */
class <?= $this->model->getClass('instanceCoreClassName') ?> extends Instance
{
<?php foreach ($this->model->getFields() as $field): ?>

    /**
     * Field `<?= $field->getName() ?>`.
     *
     * @var <?= $field->getPhpDocsTypeOutput() ?>

     */
    private $<?= $field->getName('camelCase') ?>;
<?php endforeach; ?>
<?php foreach ($this->model->getRelations() as $relation): ?>

    /**
     * Relation `<?= $relation->getName() ?>`.
     *
     * @var <?= $relation->getModelTo()->getClass('collectionClassName') ?>

     */
    private $<?= $relation->getName() ?>;
<?php endforeach; ?>

    /**
     * Constructor.
     *
     * @param string $database Optional, `null`. In which database this field is defined.
     */
    public function __construct(?string $database = null)
    {

        // Get model Instance.
        $this->model = <?= $this->model->getClass('modelClassName') ?>::getInstance();

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
    public function injectData(array $inject): self
    {

        // Inject properties.
<?php foreach ($this->model->getFields() as $field): ?>
        if (isset($inject['<?= $field->getName() ?>']) === true && $inject['<?= $field->getName() ?>'] !== null) {
            $this-><?= $field->getName('camelCase') ?> = <?= $field->getPhpTypeInputInBraces() ?>$inject['<?= $field->getName() ?>'];
        }
<?php endforeach; ?>

        // Mark all fields set.
        $this->setFields = array_keys($inject);

        return $this;
    }

    /**
     * Returns info if primary key for this record has been given.
     *
     * @return boolean
     */
    public function hasPrimaryKey(): bool
    {

        if ($this-><?= $this->model->getPrimaryKeyField()->getName('camelCase') ?> === null) {
            return false;
        }

        return true;
    }

    /**
     * Resets primary key into null - like the record is not existing in DB.
     *
     * @return self
     */
    protected function resetPrimaryKey(): self
    {

        $this-><?= $this->model->getPrimaryKeyField()->getName('camelCase') ?> = null;

        $noInSet = array_search('<?= $this->model->getPrimaryKeyField()->getName() ?>', $this->setFields);

        if (is_int($noInSet) === true) {
            unset($this->setFields[$noInSet]);
        }

        return $this;
    }
<?php foreach ($this->model->getFields() as $field): ?>

    /**
     * Getter for `<?= $field->getName() ?>` field value.
     *
     * @return <?= $field->getPhpDocsTypeOutput() ?>

     */
    public function get<?= $field->getName('pascalCase') ?>(): <?= $field->getPhpTypeOutput() ?>

    {

        return $this->getCore<?= $field->getName('pascalCase') ?>(...func_get_args());
    }

    /**
     * Core getter for `<?= $field->getName() ?>` field value.
     *
     * @return <?= $field->getPhpDocsTypeOutput() ?>

     */
    public function getCore<?= $field->getName('pascalCase') ?>(): <?= $field->getPhpTypeOutput() ?>

    {

<?= $field->getterToPhp(); ?>
    }

<?= $field->extraMethodsToPhp($this->model); ?>

    /**
     * Setter for `<?= $field->getName() ?>` field value.
     *
     * @param <?= $field->getPhpDocsTypeInput() ?> $<?= $field->getName('camelCase') ?> Value to be set.
     *
     * @return <?= $this->getClassName($this->model->getClass('instanceClass')) ?>

     */
    public function set<?= $field->getName('pascalCase') ?>(<?= $field->getPhpTypeInput() ?>$<?= $field->getName('camelCase') ?>): <?= $this->getClassName($this->model->getClass('instanceClass')) ?>

    {

        return $this->setCore<?= $field->getName('pascalCase') ?>($<?= $field->getName('camelCase') ?>);
    }

    /**
     * Core setter for `<?= $field->getName() ?>` field value.
     *
     * @param <?= $field->getPhpDocsTypeInput() ?> $<?= $field->getName('camelCase') ?> Value to be set.
     *
     * @return <?= $this->getClassName($this->model->getClass('instanceClass')) ?>

     */
    public function setCore<?= $field->getName('pascalCase') ?>(<?= $field->getPhpTypeInput() ?>$<?= $field->getName('camelCase') ?>): <?= $this->getClassName($this->model->getClass('instanceClass')) ?>

    {

        // Test value.
<?php if (method_exists($field, 'setProperType') === true): ?>
        $<?= $field->getName('camelCase') ?> = $this->grabField('<?= $field->getName() ?>')->setProperType($<?= $field->getName('camelCase') ?>);
<?php endif; ?>
        $this->grabField('<?= $field->getName() ?>')->isValueValid($<?= $field->getName('camelCase') ?>);

        // If there is nothing to be changed.
        <?= $field->compareToPhp(); ?>
            return $this;
        }

        // Save.
        $this-><?= $field->getName('camelCase') ?> = $<?= $field->getName('camelCase') ?>;

        // Note that was set.
        $this->setFields[]     = '<?= $field->getName() ?>';
        $this->changedFields[] = '<?= $field->getName() ?>';

        // Note that was changed.
        if (isset($this->fieldsValuesHistory['<?= $field->getName() ?>']) === false) {
            $this->fieldsValuesHistory['<?= $field->getName() ?>'] = [];
        }
        $this->fieldsValuesHistory['<?= $field->getName() ?>'][] = $<?= $field->getName('camelCase') ?>;

        return $this;
    }
<?php endforeach; ?>
<?php foreach ($this->model->getRelations() as $relation): ?>
<?php if ($relation->getType() === 'hasOne'): ?>

    /**
     * Returns child-Instance (one and only - for hasOne Relation type) in Relation.
     *
     * @return <?= $this->getClassName($relation->getModelTo()->getClass('instanceClass')) ?>

     */
    public function <?= $relation->getGetterName() ?>(): <?= $this->getClassName($relation->getModelTo()->getClass('instanceClass')) ?>

    {

        return $this-><?= $relation->getName() ?>->getOne();
    }

    /**
     * Call to add children (<?= $this->getClassName($relation->getModelTo()->getClass('instanceClass')) ?>) to this Instance.
     *
     * @since  v1.0
     * @return self
     */
    public function <?= $relation->getExpanderName() ?>(): self
    {

        // Get records with those pks.
        $child = new <?= $this->getClassName($relation->getModelTo()->getClass('instanceClass')) ?>(...func_get_args());

        // If we know that we need this one - read this one.
        if ($this-><?= $relation->getFieldFrom()->getGetterName() ?>() !== null) {
            $child-><?= $relation->getFieldTo()->getSetterName() ?>($this-><?= $relation->getFieldFrom()->getGetterName() ?>());
            $child->read();
        }

        // Add this child (empty or not).
        $this-><?= $relation->getAdderName() ?>($child);

        return $this;
    }

    /**
     * Adds one child-Instance to Relation Collection.
     *
     * @param <?= $this->getClassName($relation->getModelTo()->getClass('instanceClass')) ?> $instance One child-Instance of child for Relation.
     *
     * @return self
     */
    public function <?= $relation->getAdderName() ?>(<?= $this->getClassName($relation->getModelTo()->getClass('instanceClass')) ?> $instance): self
    {

        // If there is no Collection created - create one.
        if (is_null($this-><?= $relation->getName() ?>) === true) {
            $this-><?= $relation->getName() ?> = new <?= $this->getClassName($relation->getModelTo()->getClass('collectionClass')) ?>();
        }

        // Put this Instance to this Collection.
        $this-><?= $relation->getName() ?>->put($instance);

        return $this;
    }
<?php else: ?>

    /**
     * Returns child-Collection (zero or more children - for hasMany Relation type) in Relation.
     *
     * @return <?= $relation->getModelTo()->getClass('collectionClassName') ?>

     */
    public function <?= $relation->getGetterName() ?>(): <?= $this->getClassName($relation->getModelTo()->getClass('collectionClass')) ?>

    {

        // Create empty collection if there isn't any added.
        if ($this-><?= $relation->getName() ?> === null) {
            $this-><?= $relation->getName() ?> = new <?= $this->getClassName($relation->getModelTo()->getClass('collectionClass')) ?>();
            $this-><?= $relation->getName() ?>->getLogics()->addFromRelation($this->grabModel()->getRelationByName('<?= $relation->getName() ?>'));
        }

        return $this-><?= $relation->getName() ?>;
    }

    /**
     * Call to add children (<?= $relation->getModelTo()->getClass('collectionClassName') ?>) to this Instance.
     *
     * @since  v1.0
     * @return self
     */
    public function <?= $relation->getExpanderName() ?>(): self
    {

        // Get records with those pks.
        $children = new <?= $this->getClassName($relation->getModelTo()->getClass('collectionClass')) ?>(...func_get_args());
<?php if ($relation->hasLogics() === true): ?>
        $children->getLogics()->addFromRelation($this->grabModel()->getRelationByName('<?= $relation->getName() ?>'));
<?php endif; ?>

        // If we know that we need this one - read this one.
        if ($this-><?= $relation->getFieldFrom()->getGetterName() ?>() !== null) {
            $children->getLogics()->addRule(
                '<?= $relation->getFieldTo()->getName() ?>',
                $this-><?= $relation->getFieldFrom()->getGetterName() ?>()
            );
            $children->getLogics()->addFromRelation($this->grabModel()->getRelationByName('<?= $relation->getName() ?>'));
            $children->read();
        }

        // Add this child (empty or not).
        $this-><?= $relation->getAdderName() ?>($children);

        return $this;
    }

    /**
     * Adds child-Collection to Relation Collection.
     *
     * @param <?= $this->getClassName($relation->getModelTo()->getClass('collectionClass')) ?> $collection One child-Instance of child for Relation.
     *
     * @return self
     */
    public function <?= $relation->getAdderName() ?>(<?= $this->getClassName($relation->getModelTo()->getClass('collectionClass')) ?> $collection): self
    {

        // Put this Instance to this Collection.
        $this-><?= $relation->getName() ?> = $collection;

        return $this;
    }
<?php endif; ?>
<?php endforeach; ?>
}
