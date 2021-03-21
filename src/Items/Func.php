<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Items;

use Przeslijmi\Sexceptions\Exceptions\TypeHintingFailException;
use Przeslijmi\Shortquery\Exceptions\Items\FuncItemOtosetException;
use Przeslijmi\Shortquery\Exceptions\Items\FuncWrongComponentsException;
use Przeslijmi\Shortquery\Items\ContentItem;
use Przeslijmi\Sivalidator\TypeHinting;

/**
 * Func item - eg. MAX(), MIN(), etc..
 */
class Func extends ContentItem
{

    /**
     * Name of func.
     *
     * @var string
     */
    private $name;

    /**
     * Array of ContentItems - subsequent function parameters.
     *
     * @var ContentItem[]
     */
    private $items;

    /**
     * Static factory method with many possible input types.
     *
     * @param string $name  Name of the function.
     * @param array  $items Subsequent parameters.
     *
     * @todo   Add throws and proper catching of them.
     * @return Func
     */
    public static function factory(string $name, array $items) : Func
    {

        foreach ($items as $i => $item) {
            if (is_a($item, 'Przeslijmi\Shortquery\Items\ContentItem') === false) {
                if (is_array($item) === true) {
                    $items[$i] = new Vals($item);
                } elseif (is_integer($item) === true) {
                    $items[$i] = new IntVal($item);
                } elseif (is_null($item) === true) {
                    $items[$i] = new NullVal($item);
                } elseif (is_string($item) === true && substr($item, 0, 1) === '`' && substr($item, -1) === '`') {
                    $items[$i] = new Field(trim($item, '`'));
                } else {
                    $items[$i] = new Val($item);
                }
            }
        }

        return new Func($name, $items);
    }

    /**
     * Constructor.
     *
     * @param string $name  Name of the function.
     * @param array  $items Parameters.
     *
     * @throws FuncWrongComponentsException When not every given item is a ContentItem.
     */
    public function __construct(string $name, array $items)
    {

        try {
            TypeHinting::isArrayOf($items, 'Przeslijmi\Shortquery\Items\ContentItem');
        } catch (TypeHintingFailException $sexc) {
            throw new FuncWrongComponentsException([
                'Przeslijmi\Shortquery\Items\ContentItem[]',
                $sexc->getIsInFact(),
            ], 0, $sexc);
        }

        $this->name  = strtolower($name);
        $this->items = $items;

        foreach ($this->items as $item) {
            $item->setFuncParent($this);
        }
    }

    /**
     * Getter for function name.
     *
     * @return string
     */
    public function getName() : string
    {

        return $this->name;
    }

    /**
     * Getter for function items (parameters).
     *
     * @return array
     */
    public function getItems() : array
    {

        return $this->items;
    }

    /**
     * Return number of given parameters.
     *
     * @return integer
     */
    public function countItems() : int
    {

        return count($this->items);
    }

    /**
     * Getter for i-numbered item.
     *
     * @param integer $itemId Id of needed item (starting with 0 [zero]).
     *
     * @throws FuncItemOtosetException When no parameter at given id is present.
     * @return ContentItem
     */
    public function getItem(int $itemId) : ContentItem
    {

        if (isset($this->items[$itemId]) === false) {
            throw new FuncItemOtosetException([
                implode(', ', array_keys($this->items)),
                (string) $itemId,
            ]);
        }

        return $this->items[$itemId];
    }
}
