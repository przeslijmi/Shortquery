<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\ForTests\Models\Core;

use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\ForTests\Models\Cars as Cars9348;
use Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel;

/**
 * ShortQuery Collection Core class for Girl Model Collection.
 */
class GirlsCore extends Collection
{

    /**
     * Constructor.
     */
    public function __construct()
    {

        // Define Model.
        $this->model = GirlModel::getInstance();

        // Call parent (set additional logics).
        parent::__construct(...func_get_args());
    }

    /**
     * Call to add children/childrens (cars) to every Instance in this Collection.
     *
     * @since  v1.0
     * @return self
     */
    public function expandCars() : self
    {

        // Get pks (primary-keys) present in current collection.
        $pks = $this->getValuesByField('pk');
        $pks = array_unique($pks);

        // Shortcut - nothing to expand.
        if (count($pks) === 0) {
            return $this;
        }

        // Get records with those pks.
        $toAdd = new Cars9348(...func_get_args());
        $toAdd->getLogics()->addRule('owner_girl', $pks);
        $toAdd->getLogics()->addFromRelation($this->getModel()->getRelationByName('cars'));
        $toAdd->read();

        // Unpack child records to existing collection of parents.
        $this->unpack($toAdd, $this->getModel()->getRelationByName('cars'));

        return $this;
    }

    /**
     * Call to add children/childrens (fastCars) to every Instance in this Collection.
     *
     * @since  v1.0
     * @return self
     */
    public function expandFastCars() : self
    {

        // Get pks (primary-keys) present in current collection.
        $pks = $this->getValuesByField('pk');
        $pks = array_unique($pks);

        // Shortcut - nothing to expand.
        if (count($pks) === 0) {
            return $this;
        }

        // Get records with those pks.
        $toAdd = new Cars9348(...func_get_args());
        $toAdd->getLogics()->addRule('owner_girl', $pks);
        $toAdd->getLogics()->addFromRelation($this->getModel()->getRelationByName('fastCars'));
        $toAdd->read();

        // Unpack child records to existing collection of parents.
        $this->unpack($toAdd, $this->getModel()->getRelationByName('fastCars'));

        return $this;
    }
}
