<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Collection;

use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Exceptions\Data\CollectionSliceNotPossibleException;

/**
 * Parent for all Collection of Instances (records).
 */
abstract class Tools
{

    /**
     * Changes content of given Collection to be identical with given raw array.
     *
     * *BEWARE!* This might change order of Collection.
     *
     * @param array $new How Collection should be visible.
     *
     * @return self
     */
    public function makeContentAnalogousToArray(array $new) : self
    {

        // Look at every new and update (by 0...i id).
        foreach ($new as $id => $newContent) {

            try {

                // If one exists in Collection already.
                $instance = $this->getOne($id);

            } catch (CollectionSliceNotPossibleException $exc) {

                // Create new Instance and fill with data.
                $instanceClass = $this->getModel()->getClass('instanceClass');
                $instance      = new $instanceClass();

                // Put this new, empty instance into this Collection.
                $this->put($instance);
            }

            // Fill empty or different Instance with new values.
            foreach ($newContent as $fieldName => $value) {
                $setterName = $this->getModel()->getFieldByName($fieldName)->getSetterName();
                $instance->$setterName($value);
            }
        }//end foreach

        // Lvd.
        $countOld = count($this->get());
        $countNew = count($new);

        // Delete if needed.
        if ($countOld > $countNew) {
            for ($i = $countNew; $i < $countOld; ++$i) {
                $this->getOne($i)->defineIsToBeDeleted(true);
            }
        }

        return $this;
    }

    /**
     * Changes content of given Collection to be identical with given raw array but both splitted by value of one field.
     *
     * *BEWARE!* This might change order of Collection.
     *
     * @param Field $field Field to be used to split.
     * @param array $new   How Collection should be visible.
     *
     * @return self
     */
    public function makeSplittedContentsAnalogousToArray(
        Field $field,
        array $new
    ) : self {

        // Split Collections.
        $collections = $this->splitByField($field, array_keys($new));

        // Take parts (keys of splitting).
        $keys = array_keys($collections);

        // Change states to every key of splitting.
        foreach ($keys as $key) {

            // There is nothing in new.
            if (isset($new[$key]) === true) {
                $collections[$key]->makeContentAnalogousToArray($new[$key]);
            } else {
                // Mark all elements of this collection to be deleted.
                foreach ($collections[$key]->get() as $item) {
                    $item->defineIsToBeDeleted(true);
                }
            }
        }

        // Eliminate keys from final array.
        $collections = array_values($collections);

        // Call to recompose.
        $this->recomposeItemsFrom(...$collections);

        return $this;
    }

    /**
     * Splits Collection by given field into smaller Collections.
     *
     * @param Field $field         Field to be used to split.
     * @param array $forcedNewKeys Optional, null. If set those given new keys will be added with empty Collections.
     *
     * @return self
     */
    public function splitByField(Field $field, ?array $forcedNewKeys = null) : array
    {

        // Lvd.
        $getter = $field->getGetterName();
        $result = [];

        // Add forced new keys if asked.
        if (is_array($forcedNewKeys) === true) {
            foreach ($forcedNewKeys as $newKey) {
                $result[$newKey] = clone $this;
                $result[$newKey]->getLogics()->addRule($field->getName(), $newKey);
            }
        }

        // Now scan thru Collection and put instances where them belong.
        foreach ($this->get() as $instance) {

            // Get splitter value.
            $splitterValue = $instance->$getter();

            // If there is no Collection for this splitter value - create one.
            if (isset($result[$splitterValue]) === false) {
                $result[$splitterValue] = clone $this;
                $result[$splitterValue]->getLogics()->addRule($field->getName(), $splitterValue);
            }

            // Put instance into Collection for this spitter value.
            $result[$splitterValue]->put($instance);
        }

        return $result;
    }

    /**
     * Gather all splitted Collection back to mother (`$this`) Collection.
     *
     * @param Collection ...$collections Splitted collection to gather into `$this`.
     *
     * @return self
     */
    public function recomposeItemsFrom(Collection ...$collections)
    {

        // Clear this (mother) Collection.
        $this->clear();

        // Put each Instance from each Collection into mother.
        foreach ($collections as $collection) {
            foreach ($collection->get() as $instance) {
                $this->put($instance);
            }
        }

        return $this;
    }
}
