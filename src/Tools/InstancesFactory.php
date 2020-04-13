<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Tools;

use Throwable;
use Exception;
use Przeslijmi\Sexceptions\Exceptions\ClassDonoexException;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Sexceptions\Exceptions\ParamWrosynException;
use Przeslijmi\Sexceptions\Exceptions\ParamWrotypeException;
use Przeslijmi\Sexceptions\Exceptions\TypeHintingFailException;
use Przeslijmi\Sivalidator\TypeHinting;
use Przeslijmi\Shortquery\Data\Collection;

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
     * @throws Exception InstancesFactory can take only collection name or collection instance as argument.
     * @throws ClassDonoexException On creatingInstanceForShortquery.
     * @throws ParamWrosynException On className.
     * @throws ParamWrotypeException On props.
     * @throws MethodFopException On constructorOfInstancesFailed.
     * @throws MethodFopException On creatingInstanceFromArrayFailed.
     *
     * @return object Model object.
     */
    public static function fromArray($classOrClassName, array $props) : object
    {

        // Lvd.
        $className = null;
        $instance  = null;

        // Verify argument.
        if (is_string($classOrClassName) === true) {
            $className = $classOrClassName;
        } elseif (is_object($classOrClassName) === true) {
            $instance = $classOrClassName;
        } else {
            throw new Exception('InstancesFactory can take only collection name or collection instance as argument');
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
                    $instance = new $className(null);
                } catch (Throwable $e) {
                    throw (new MethodFopException('constructorOfInstancesFailed', $e))->addInfo('class', $className);
                }
            }//end if

            // Inject properties to object.
            $instance->injectData($props);

        } catch (Throwable $e) {
            throw (new MethodFopException('creatingInstanceFromArrayFailed', $e))->addInfo('class', $className);
        }//end try

        return $instance;
    }
}
