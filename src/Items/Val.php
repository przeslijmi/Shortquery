<?php declare(strict_types=1);

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
     * Factory method.
     *
     * @param string $value String.
     *
     * @since  v1.0
     * @return Val
     */
    public static function factory(string $value, ?string $alias = null) : Val
    {

        $val = new self($value);

        if ($alias !== null) {
            $val->setAlias($alias);
        }

        return $val;
    }

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
