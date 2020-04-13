<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Items;

/**
 * Value item - integer.
 */
class IntVal extends ContentItem
{

    /**
     * Holder of value.
     *
     * @var integer
     */
    private $value;

    /**
     * Constructor.
     *
     * @param integer $value String.
     */
    public function __construct(int $value)
    {

        $this->value = $value;
    }

    /**
     * Getter of value.
     *
     * @return array Array of values.
     */
    public function getValue() : int
    {

        return $this->value;
    }
}
