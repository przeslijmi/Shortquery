
namespace <?= $this->model->getClass('namespaceCore') ?>;

use Przeslijmi\Shortquery\Data\Field\DateField;
use Przeslijmi\Shortquery\Data\Field\DecimalField;
use Przeslijmi\Shortquery\Data\Field\EnumField;
use Przeslijmi\Shortquery\Data\Field\IntField;
use Przeslijmi\Shortquery\Data\Field\JsonField;
use Przeslijmi\Shortquery\Data\Field\VarCharField;
use Przeslijmi\Shortquery\Data\Field\SetField;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Data\Relation\HasManyRelation;
use Przeslijmi\Shortquery\Data\Relation\HasOneRelation;

/**
 * ShortQuery Model definition for <?= $this->model->getClass('instanceClassName') ?>.
 */
class <?= $this->model->getClass('modelClassName') ?> extends Model
{

    /**
     * Holder of model (to prevent multicreation).
     *
     * @var <?= $this->model->getClass('modelClassName') ?>

     */
    private static $instance;

    /**
     * Retrieves only one instance to prevent multicreation.
     *
     * @return <?= $this->model->getClass('modelClassName') ?>

     */
    public static function getInstance() : <?= $this->model->getClass('modelClassName') ?>

    {

        if (is_null(self::$instance) === true) {
            self::$instance = new <?= $this->model->getClass('modelClassName') ?>();
        }

        return self::$instance;
    }

    /**
     * Constructor.
     *
     * @since v1.0
     */
    public function __construct()
    {

        // Define Model.
        $this->setName('<?= $this->model->getName() ?>');
        $this->setEngine('<?= $this->model->getEngine() ?>');
        $this->setNamespace('<?= $this->model->getNamespace() ?>');
        $this->setInstanceClassName('<?= $this->model->getInstanceClassName() ?>');
        $this->setCollectionClassName('<?= $this->model->getCollectionClassName() ?>');

<?php if (count($this->model->getFields()) > 0): ?>
        // Define Fields of Model.
<?php foreach ($this->model->getFields() as $field): ?>
        $this->addField(<?= $field->toPhp() ?>);
<?php endforeach; ?>
<?php endif; ?>

<?php if (count($this->model->getRelations()) > 0): ?>
        // Define Relations of Model.
<?php foreach ($this->model->getRelations() as $relation): ?>
        $this->addRelation(<?= $relation->toPhp() ?>);
<?php endforeach; ?>
<?php endif; ?>
    }
}
