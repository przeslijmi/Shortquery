<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\Mysql\ToString;

use Przeslijmi\Shortquery\Items\NullVal;

/**
 * Converts NullVal element into string.
 *
 * ## Usage example
 * ```
 * $val = new NullVal();
 * echo (new NullValToString($val))->toString(); // will return NULL
 * ```
 */
class NullValToString
{


    /**
     * Converts to string.
     *
     * @since  v1.0
     * @return string
     */
    public function toString() : string
    {

        return 'NULL';
    }
}
