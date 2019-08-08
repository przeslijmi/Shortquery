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
     *
     * @since v1.0
     */
    public function __construct(int $value)
    {

        $this->value = $value;
    }

    /**
     * Getter of value.
     *
     * @since  v1.0
     * @return array Array of values.
     */
    public function getValue() : int
    {

        return $this->value;
    }
}
