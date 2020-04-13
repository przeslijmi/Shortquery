<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Items;

/**
 * Value item - true.
 */
class TrueVal extends ContentItem
{

    /**
     * Getter of value.
     *
     * @return boolean
     */
    public function getValue()
    {

        return true;
    }
}
