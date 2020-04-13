<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\ForTests\Models\Core;

use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\ForTests\Models\Core\ThingModel;

/**
 * ShortQuery Collection Core class for Thing Model Collection.
 */
class ThingsCore extends Collection
{

    /**
     * Constructor.
     */
    public function __construct()
    {

        // Define Model.
        $this->model = ThingModel::getInstance();

        // Call parent (set additional logics).
        parent::__construct(...func_get_args());
    }
}
