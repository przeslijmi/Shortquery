<?php

namespace Przeslijmi\Shortquery\Tools;

use Przeslijmi\Sexceptions\Exceptions\ClassDonoexException;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Sexceptions\Exceptions\ParamWrosynException;
use Przeslijmi\Sexceptions\Exceptions\ParamWrotypeException;
use Przeslijmi\Sexceptions\Exceptions\TypeHintingFailException;
use Przeslijmi\Sivalidator\TypeHinting;

/**
 * Creates model objects from array with values.
 */
class InstancesFactory
{

    /**
     * Creates model objects from array with values.
     *
     * @param object|string $classOrClassName Model class or model class name.
     * @param array         $props            Array with properties of object.
     *
     * @since  v1.0
     * @throws ClassDonoexException On creatingInstanceForShortquery.
     * @throws ParamWrosynException On className.
     * @throws ParamWrotypeException On props.
     * @throws MethodFopException On constructorOfInstancesFailed.
     * @throws MethodFopException On creatingInstanceFromArrayFailed.
     * @return object Model object.
     */
    public static function fromArray($classOrClassName, array $props) : object
    {

        $className = null;
        $instance  = null;

        if (is_string($classOrClassName) === true) {
            $className = $classOrClassName;
        } elseif (is_object($classOrClassName) === true) {
            $instance = $classOrClassName;
        } else {
            die('sdadfgseawf345w3qrfestbr');
        }

        try {

            if (is_null($className) === false) {

                // Chk arg 1.
                try {
                    if (class_exists($className) === false) {
                        throw new ClassDonoexException('creatingInstanceForShortquery', $className);
                    }
                } catch (ClassDonoexException $e) {
                    throw new ParamWrosynException('className', $className, $e);
                }

                // Chk arg 2.
                try {
                    TypeHinting::isArrayOfStrings($props, true);
                } catch (TypeHintingFailException $e) {
                    throw new ParamWrotypeException('props', 'string[]', $e->getIsInFact(), $e);
                }

                // Create instance.
                try {
                    $instance = new $className();
                } catch (Sexception $e) {
                    throw (new MethodFopException('constructorOfInstancesFailed', $e))->addInfo('class', $className);
                }
            }//end if

            $instance = self::fromArrayDo($instance, $props);

        } catch (\Exception $e) {
            throw (new MethodFopException('creatingInstanceFromArrayFailed', $e))->addInfo('class', $className);
        }//end try

        return $instance;
    }

    /**
     * Helper method doing actual convertion from array to objects.
     *
     * @param object $instance Empty object of model.
     * @param array  $props    Properties.
     *
     * @since  v1.0
     * @throws MethodFopException On settingValueForInstanceFailed.
     * @return object Model object.
     */
    private static function fromArrayDo(object $instance, array $props) : object
    {

        // Fill up instance.
        foreach ($props as $propName => $propValue) {

            $propNameExploded = explode('_', $propName);
            array_walk(
                $propNameExploded,
                function (&$value) {
                    $value = ucfirst($value);
                }
            );
            $setterName = 'set' . implode('', $propNameExploded);

            try {
                call_user_func([ $instance, $setterName ], $propValue);
            } catch (\Exception $e) {
                throw (new MethodFopException('settingValueForInstanceFailed', $e))
                    ->addInfo('class', get_class($instance))
                    ->addInfo('name', $propName)
                    ->addInfo('value', $propValue);
            }
        }

        return $instance;
    }
}
