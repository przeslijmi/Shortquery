<?php

namespace Przeslijmi\Shortquery\Items;

/**
 * Value item - strings.
 */
class Val extends ContentItem
{

    /**
     * Holder of value.
     *
     * @var string
     */
    private $value;

    /**
     * Constructor.
     *
     * @param string $value String.
     *
     * @since v1.0
     */
    public function __construct(string $value)
    {

        $this->value = $value;
    }

    /**
     * Getter of value.
     *
     * @since  v1.0
     * @return array Array of values.
     */
    public function getValue() : string
    {

        return $this->value;
    }
}
