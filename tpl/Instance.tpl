
namespace <?= $this->model->getClass('namespace') ?>;

use <?= $this->model->getClass('namespaceCore') ?>\<?= $this->model->getClass('instanceCoreClassName') ?>;

/**
 * ShortQuery Collection class for <?= $this->model->getClass('instanceClassName') ?> Model.
 */
class <?= $this->model->getClass('instanceClassName') ?> extends <?= $this->model->getClass('instanceCoreClassName') ?>

{

    /**
     * Constructor.
     */
    public function __construct()
    {

        parent::__construct();
    }
}
