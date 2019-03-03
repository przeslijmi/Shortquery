<?php

namespace Przeslijmi\Shortquery\Tools;

use Przeslijmi\Sexceptions\Exceptions\ClassDonoexException;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Sexceptions\Exceptions\ParamWrosynException;
use Przeslijmi\Sexceptions\Exceptions\ParamWrotypeException;
use Przeslijmi\Sexceptions\Exceptions\TypeHintingFailException;
use Przeslijmi\Sivalidator\TypeHinting;

class InstancesFactory
{

    public static function fromArray($classOrClassName, array $props) : object
    {

        $className = null;
        $instance = null;

        if (is_string($classOrClassName) === true) {
            $className = $classOrClassName;
        } else if (is_object($classOrClassName) === true) {
            $instance = $classOrClassName;
        } else {
            die('sdadfgseawf345w3qrfestbr');
        }

        try {

            if (is_null($className) === false) {

                // chk arg 1
                try {
                    if (!class_exists($className)) {
                        throw new ClassDonoexException('creatingInstanceForShortquery', $className);
                    }
                } catch (ClassDonoexException $e) {
                    throw new ParamWrosynException('className', $className, $e);
                }

                // chk arg 2
                try {
                    TypeHinting::isArrayOfStrings($props, true);
                } catch (TypeHintingFailException $e) {
                    throw new ParamWrotypeException('props', 'string[]', $e->getIsInFact(), $e);
                }

                // create instance
                try {
                    $instance = new $className();
                } catch (Sexception $e) {
                    throw (new MethodFopException('constructorOfInstancesFailed', $e))->addInfo('class', $className);
                }
            }

            $instance = self::fromArrayDo($instance, $props);

        } catch (\Exception $e) {
            throw (new MethodFopException('creatingInstanceFromArrayFailed', $e))->addInfo('class', $className);
        }

        return $instance;
    }

    private static function fromArrayDo(object $instance, array $props) : object
    {

        // fill up instance
        foreach ($props as $propName => $propValue) {

            $propNameExploded = explode('_', $propName);
            array_walk($propNameExploded, function(&$value, $key) {
                $value = ucfirst($value);
            });
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
