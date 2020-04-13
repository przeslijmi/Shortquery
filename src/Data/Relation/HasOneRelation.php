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
     */
    public function __construct(string $name)
    {

        $this->setType('hasOne');
        $this->setName($name);
    }

    /**
     * Prepare PHP commands to create this Relation in model.
     *
     * @return string
     */
    public function toPhp() : string
    {

        // Lvd.
        $indent = '    ';

        // Result.
        $result  = "\n";
        $result .= str_repeat($indent, 3) . '( new HasOneRelation(\'' . $this->getName() . '\') )' . "\n";
        $result .= str_repeat($indent, 4) . '->setFieldFrom(\'' . $this->getFieldFromAsName() . '\')' . "\n";
        $result .= str_repeat($indent, 4) . '->setModelTo(\'' . $this->getModelToAsName() . '\')' . "\n";
        $result .= str_repeat($indent, 4) . '->setFieldTo(\'' . $this->getFieldToAsName() . '\')' . "\n";

        foreach ($this->getLogicsSyntax() as $syntaxLine) {
            $result .= str_repeat($indent, 4) . $syntaxLine . "\n";
        }

        $result .= str_repeat($indent, 2);

        return $result;
    }
}
