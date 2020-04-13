<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use Przeslijmi\Sexceptions\Exceptions\ParamWrotypeException;
use Przeslijmi\Sexceptions\Exceptions\TypeHintingFailException;
use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Shortquery\Items\LogicItem;
use Przeslijmi\Sivalidator\TypeHinting;

/**
 * Abstract parent class for every engine.
 */
abstract class Engine
{

    /**
     * Database name that uses this engine (has to be present at PRZESLIJMI_SHORTQUERY_DATABASES).
     *
     * @var string
     */
    protected $database;

    /**
     * Array of logics.
     *
     * @var LogicItem[]
     */
    protected $logics = [];
}
