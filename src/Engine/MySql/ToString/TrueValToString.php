<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\Mysql\ToString;

use Przeslijmi\Shortquery\Items\TrueVal;

/**
 * Converts TrueVal element into string.
 *
 * ## Usage example
 * ```
 * $val = new TrueVal();
 * echo (new TrueValToString($val))->toString(); // will return NULL
 * ```
 */
class TrueValToString
{


    /**
     * Converts to string.
     *
     * @since  v1.0
     * @return string
     */
    public function toString() : string
    {

        return 'TRUE';
    }
}
