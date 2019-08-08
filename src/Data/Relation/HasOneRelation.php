<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Relation;

use Przeslijmi\Shortquery\Data\Relation;

/**
 * Defines Has One Relation between two Models.
 */
class HasOneRelation extends Relation
{

    /**
     * Constructor.
     *
     * @param string $name Name of Relation.
     *
     * @since v1.0
     */
    public function __construct(string $name)
    {

        $this->setType('hasOne');
        $this->setName($name);
    }

    /**
     * Prepare PHP commands to create this Relation in model.
     *
     * @since  v1.0
     * @return string
     */
    public function toPhp() : string
    {

        // Lvd.
        $indent = '    ';

        // Result.
        $result  = PHP_EOL;
        $result .= str_repeat($indent, 3) . '(new HasOneRelation(\'' . $this->getName() . '\'))' . PHP_EOL;
        $result .= str_repeat($indent, 4) . '->setFieldFrom(\'' . $this->getFieldFromAsName() . '\')' . PHP_EOL;
        $result .= str_repeat($indent, 4) . '->setModelTo(\'' . $this->getModelToAsName() . '\')' . PHP_EOL;
        $result .= str_repeat($indent, 4) . '->setFieldTo(\'' . $this->getFieldToAsName() . '\')' . PHP_EOL;

        foreach ($this->getSyntax() as $syntaxLine) {
            $result .= str_repeat($indent, 4) . $syntaxLine . PHP_EOL;
        }

        $result .= str_repeat($indent, 2);

        return $result;
    }
}
