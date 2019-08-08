<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Items;

use Przeslijmi\Sexceptions\Exceptions\ParamOtosetException;
use Przeslijmi\Sexceptions\Exceptions\ParamWrotypeException;
use Przeslijmi\Sexceptions\Exceptions\TypeHintingFailException;
use Przeslijmi\Sivalidator\TypeHinting;

/**
 * Func item - eg. MAX(), MIN(), etc..
 *
 * @todo alias
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
     * @since  v1.0
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
     * @since  v1.0
     * @throws ParamWrotypeException When not every given item is a ContentItem.
     */
    public function __construct(string $name, array $items)
    {

        try {
            TypeHinting::isArrayOf($items, 'Przeslijmi\Shortquery\Items\ContentItem');
        } catch (TypeHintingFailException $e) {
            throw new ParamWrotypeException('items', 'ContentItem[]', $e->getIsInFact());
        }


        // Exclude duplicates.
        /*$foundValues = [];

        foreach ($items as $i => $item) {
            if (isset($foundValues[$item->getValue()]) === true) {
                unset($items[$i]);
            }

            $foundValues[$item->getValue()] = true;
        }*/

        // $items = array_slice($items, 0, 1);
        // var_dump($items[0]->getValue());
        // die;

        $this->name  = strtolower($name);
        $this->items = $items;

        foreach ($this->items as $item) {
            $item->setFuncParent($this);
        }
    }

    /**
     * Getter for function name.
     *
     * @since  v1.0
     * @return string
     */
    public function getName() : string
    {

        return $this->name;
    }

    /**
     * Getter for function items (parameters).
     *
     * @since  v1.0
     * @return array
     */
    public function getItems() : array
    {

        return $this->items;
    }

    /**
     * Return number of given parameters.
     *
     * @since  v1.0
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
     * @since  v1.0
     * @throws ParamOtosetException When no parameter at given id is present.
     * @return ContentItem
     */
    public function getItem(int $itemId) : ContentItem
    {

        if (isset($this->items[$itemId]) === false) {
            throw (new ParamOtosetException('itemId', array_keys($this->items), (string) $itemId))
                ->addInfo('funcName', $this->name);
        }

        return $this->items[$itemId];
    }
}
