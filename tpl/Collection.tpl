
namespace <?= $this->model->getClass('namespace') ?>;

use <?= $this->model->getClass('namespaceCore') ?>\<?= $this->model->getClass('collectionCoreClassName') ?>;

/**
 * ShortQuery Collection class for <?= $this->model->getClass('instanceClassName') ?> Model.
 *
 * This is a `<shortquery-role:collection>`.
 */
class <?= $this->model->getClass('collectionClassName') ?> extends <?= $this->model->getClass('collectionCoreClassName') ?>

{
}
