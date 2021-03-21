<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Tools;

use Przeslijmi\Sexceptions\Exceptions\TypeHintingFailException;
use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Shortquery\Exceptions\Data\InstanceClassDonoexException;
use Przeslijmi\Shortquery\Exceptions\Data\InstanceConstructionFopException;
use Przeslijmi\Shortquery\Exceptions\Data\InstanceCreationWrongParamException;
use Przeslijmi\Shortquery\Exceptions\Data\InstanceFopPropsNoStringsException;
use Przeslijmi\Sivalidator\TypeHinting;
use Throwable;

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
     * @throws InstanceCreationWrongParamException When wrong object is used to create Instance.
     * @throws InstanceClassDonoexException When instance dos not exists.
     * @throws InstanceFopPropsNoStringsException When trying to create instance of nonexisting class.
     * @throws InstanceConstructionFopException When creation of instance failed on process.
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
            throw new InstanceCreationWrongParamException();
        }

        try {

            if (is_null($className) === false) {

                // Chk arg 1.
                if (class_exists($className) === false) {
                    throw new InstanceClassDonoexException([ $className ]);
                }

                // Chk arg 2.
                try {
                    TypeHinting::isArrayOfStrings($props, true);
                } catch (TypeHintingFailException $sexc) {
                    throw new InstanceFopPropsNoStringsException([ $className ], 0, $sexc);
                }

                // Create instance.
                $instance = new $className(null);
            }//end if

            // Inject properties to object.
            $instance->injectData($props);

        } catch (Throwable $sexc) {
            throw new InstanceConstructionFopException([ (string) $className ], 0, $sexc);
        }//end try

        return $instance;
    }
}
