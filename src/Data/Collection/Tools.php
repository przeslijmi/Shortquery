<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Collection;

use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Exceptions\Records\CollectionSliceNotPossibleException;

/**
 * Parent for all Collection of Instances (records).
 */
abstract class Tools
{

    public function makeContentAnalogousToArray(array $new) : self
    {

        // Look at every new and update (by 0...i id).
        foreach ($new as $id => $newContent) {

            try {

                // If one exists in collection already.
                $instance = $this->getOne($id);

            } catch (CollectionSliceNotPossibleException $exc) {

                //
                $instanceClass = $this->getModel()->getClass('instanceClass');
                $instance      = new $instanceClass();

                $this->put($instance);
            }

            foreach ($newContent as $fieldName => $value) {
                $setterName = $this->getModel()->getFieldByName($fieldName)->getSetterName();
                $instance->$setterName($value);
            }
        }

        $countOld = count($this->get());
        $countNew = count($new);

        if ($countOld > $countNew) {
            for ($i = $countNew; $i < $countOld; ++$i) {
                $this->getOne($i)->defineIsToBeDeleted(true);
            }
        }

        return $this;
    }

    public function makeSplittedContentsAnalogousToArray(
        Field $field,
        array $new
    ) : self {

        $collections = $this->splitByField($field, array_keys($new));

        $parts = array_keys($collections);

        // Change states of events.
        foreach ($parts as $part) {
            $collections[$part]->makeContentAnalogousToArray($new[$part]);
        }
        $collections = array_values($collections);

        $this->recomposeItemsFrom(...$collections);

        return $this;
    }

    public function splitByField(Field $field, ?array $forcedNewKeys = null) : array
    {

        $getter = $field->getGetterName();
        $result = [];

        if (is_array($forcedNewKeys) === true) {
            foreach ($forcedNewKeys as $newKey) {
                $result[$newKey] = clone $this;
                $result[$newKey]->clear();
                $result[$newKey]->getLogics()->addRule($field->getName(), $newKey);
            }
        }

        foreach ($this->get() as $instance) {

            $splitter = $instance->$getter();

            if (isset($result[$splitter]) === false) {
                $result[$splitter] = clone $this;
                $result[$splitter]->clear();
                $result[$splitter]->getLogics()->addRule($field->getName(), $splitter);
            }

            $result[$splitter]->put($instance);
        }

        return $result;
    }

    public function recomposeItemsFrom()
    {

        $this->clear();

        foreach (func_get_args() as $collection) {
            foreach ($collection->get() as $instance) {
                $this->put($instance);
            }
        }

        return $this;
    }
}
