
namespace <?= $this->model->getClass('namespaceCore') ?>;

<?php

$this->addUse('Przeslijmi\Shortquery\Data\Field');
$this->addUse('Przeslijmi\Shortquery\Data\Collection');
$this->addUse($this->model->getClass('namespaceCore') . '\\' . $this->model->getClass('modelClassName'));

foreach ($this->model->getRelations() as $relation):
    $this->addUse($relation->getModelTo()->getClass('collectionClass'));
endforeach;

$this->showNamespaces();
?>

/**
 * ShortQuery Collection Core class for <?= $this->model->getClass('instanceClassName') ?> Model Collection.
 *
 * This is a `<shortquery-role:collection-core>`.
 */
class <?= $this->model->getClass('collectionCoreClassName') ?> extends Collection
{

    /**
     * Constructor.
     */
    public function __construct()
    {

        // Define Model.
        $this->model = <?= $this->model->getClass('modelClassName') ?>::getInstance();

        // Call parent (set additional logics).
        parent::__construct(...func_get_args());
    }
<?php foreach ($this->model->getRelations() as $relation): ?>

    /**
     * Call to add children/childrens (<?= $relation->getName() ?>) to every Instance in this Collection.
     *
     * @since  v1.0
     * @return self
     */
    public function <?= $relation->getExpanderName() ?>(): self
    {

        // Get pks (primary-keys) present in current collection.
        $pks = $this->getValuesByField('<?= $relation->getFieldFrom()->getName() ?>');
        $pks = array_unique($pks);

        // Shortcut - nothing to expand.
        if (count($pks) === 0) {
            return $this;
        }

        // Get records with those pks.
        $toAdd = new <?= $this->getClassName($relation->getModelTo()->getClass('collectionClass')) ?>(...func_get_args());
        $toAdd->getLogics()->addRule('<?= $relation->getFieldTo()->getName() ?>', $pks);
        $toAdd->getLogics()->addFromRelation($this->getModel()->getRelationByName('<?= $relation->getName() ?>'));
        $toAdd->read();

        // Unpack child records to existing collection of parents.
        $this->unpack($toAdd, $this->getModel()->getRelationByName('<?= $relation->getName() ?>'));

        return $this;
    }
<?php endforeach; ?>
}
