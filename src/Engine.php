<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use Przeslijmi\Shortquery\Items\LogicItem;

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
