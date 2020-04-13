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
     * @param string      $value String.
     * @param null?string $alias Optional. Name of alias to use with this Item.
     *
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
     */
    public function __construct(string $value)
    {

        $this->value = $value;
    }

    /**
     * Getter of value.
     *
     * @return array Array of values.
     */
    public function getValue() : string
    {

        return $this->value;
    }
}
