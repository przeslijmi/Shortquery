<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\ForTests\Models\Core;

use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\ForTests\Models\Core\CarModel;
use Przeslijmi\Shortquery\ForTests\Models\Girls as Girls5648;

/**
 * ShortQuery Collection Core class for Car Model Collection.
 */
class CarsCore extends Collection
{

    /**
     * Constructor.
     */
    public function __construct()
    {

        // Define Model.
        $this->model = CarModel::getInstance();

        // Call parent (set additional logics).
        parent::__construct(...func_get_args());
    }

    /**
     * Call to add children/childrens (oneOwnerGirl) to every Instance in this Collection.
     *
     * @since  v1.0
     * @return self
     */
    public function expandOneOwnerGirl() : self
    {

        // Get pks (primary-keys) present in current collection.
        $pks = $this->getValuesByField('owner_girl');
        $pks = array_unique($pks);

        // Shortcut - nothing to expand.
        if (count($pks) === 0) {
            return $this;
        }

        // Get records with those pks.
        $toAdd = new Girls5648(...func_get_args());
        $toAdd->getLogics()->addRule('pk', $pks);
        $toAdd->getLogics()->addFromRelation($this->getModel()->getRelationByName('oneOwnerGirl'));
        $toAdd->read();

        // Unpack child records to existing collection of parents.
        $this->unpack($toAdd, $this->getModel()->getRelationByName('oneOwnerGirl'));

        return $this;
    }

    /**
     * Call to add children/childrens (oneOwnerGirlWithSnapchat) to every Instance in this Collection.
     *
     * @since  v1.0
     * @return self
     */
    public function expandOneOwnerGirlWithSnapchat() : self
    {

        // Get pks (primary-keys) present in current collection.
        $pks = $this->getValuesByField('owner_girl');
        $pks = array_unique($pks);

        // Shortcut - nothing to expand.
        if (count($pks) === 0) {
            return $this;
        }

        // Get records with those pks.
        $toAdd = new Girls5648(...func_get_args());
        $toAdd->getLogics()->addRule('pk', $pks);
        $toAdd->getLogics()->addFromRelation($this->getModel()->getRelationByName('oneOwnerGirlWithSnapchat'));
        $toAdd->read();

        // Unpack child records to existing collection of parents.
        $this->unpack($toAdd, $this->getModel()->getRelationByName('oneOwnerGirlWithSnapchat'));

        return $this;
    }
}
