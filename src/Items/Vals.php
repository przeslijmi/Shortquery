<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Items;

/**
 * Values item - array of strings.
 */
class Vals extends ContentItem
{

    /**
     * Holder of value.
     *
     * @var string[]
     */
    private $value;

    /**
     * Constructor.
     *
     * @param array $value Set of strings.
     *
     * @since v1.0
     */
    public function __construct(array $value)
    {

        $this->value = $value;
    }

    /**
     * Getter of value.
     *
     * @since  v1.0
     * @return array Array of values.
     */
    public function getValues() : array
    {

        return $this->value;
    }
}
