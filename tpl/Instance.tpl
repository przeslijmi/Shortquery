
namespace <?= $this->model->getClass('namespace') ?>;

use <?= $this->model->getClass('namespaceCore') ?>\<?= $this->model->getClass('instanceCoreClassName') ?>;

/**
 * ShortQuery Instance class for <?= $this->model->getClass('instanceClassName') ?> Model.
 *
 * This is a `<shortquery-role:instance>`.
 */
class <?= $this->model->getClass('instanceClassName') ?> extends <?= $this->model->getClass('instanceCoreClassName') ?>

{
}
