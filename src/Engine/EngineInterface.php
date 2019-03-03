<?php

namespace Przeslijmi\Shortquery\Engine;

/**
 * Interface for engines.
 */
interface EngineInterface
{

    /**
     * Calls engine to get/select/read data.
     *
     * @return array Array of Instances (ef. Car[]) with records.
     */
    public function read() : array;
}
