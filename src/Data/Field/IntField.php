<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Field;

use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Data\FieldInterface;

/**
 * Field to use - int.
 */
class IntField extends Field implements FieldInterface
{

    /**
     * Maximum length of the value in Field.
     *
     * @var integer
     */
    private $maxLength;

    /**
     * Constructor.
     *
     * @param string $name Name of Field.
     * @param boolean $notNull Opt., false. If true - null value is not accepted.
     *
     * @since v1.0
     */
    public function __construct(string $name, bool $notNull = false)
    {

        // Create parent.
        parent::__construct($name, $notNull);

        // Define.
        $this->setMaxLength(11);
        $this->setType('IntField');
        $this->setEngineType('int');
        $this->setPhpType('int');
    }

    /**
     * Setter for `maxLength`.
     *
     * @param integer $maxLength Maximum length of the value in Field.
     *
     * @since  v1.0
     * @return self
     */
    public function setMaxLength(int $maxLength) : self
    {

        // On too low.
        if ($maxLength < 1) {
            die('siaofj49fjwadwfdsrefer');
        }

        // On too high.
        if ($maxLength > 11) {
            die('siaofj49fjwadwerfrefer');
        }

        // Save.
        $this->maxLength = $maxLength;

        return $this;
    }

    /**
     * Getter for `maxLength`.
     *
     * @return integer
     */
    public function getMaxLength() : int
    {

        return $this->maxLength;
    }

    /**
     * Checks if value of the Field is valid according to this type.
     *
     * @param null|int $value Value to be checked.
     *
     * @since  v1.0
     * @return boolean
     */
    public function isValueValid(?int $value) : bool
    {

        // If null - it is valid.
        if (is_null($value) === true) {
            return true;
        }

        // Checks.
        if (mb_strlen((string) $value) > $this->maxLength) {
            die('IntField inproper value too long ' . $value . ' (max length ' . $this->maxLength . ', name: ' . $this->getName() . ')');
        }

        return true;
    }

    /**
     * Prepare PHP commands to create this Field in model.
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
        $result .= str_repeat($indent, 3) . '(new IntField(\'' . $this->getName() . '\', ';
        $result .= var_export($this->isNotNull(), true) . '))' . PHP_EOL;
        $result .= str_repeat($indent, 4) . '->setMaxLength(' . $this->getMaxLength() . ')' . PHP_EOL;
        $result .= str_repeat($indent, 4) . '->setPk(' . var_export($this->isPrimaryKey(), true) . ')' . PHP_EOL;
        $result .= str_repeat($indent, 2);

        return $result;
    }

    public function getterToPhp() : string
    {

        return $this->ln(2, 'return $this->' . $this->getName('camelCase') . ';');
    }

    public function compareToPhp() : string
    {

        $php  = '';
        $php .= 'if ($this->' . $this->getName('camelCase') . ' === $' . $this->getName('camelCase') . ') {';

        return $php;
    }

    public function extraMethodsToPhp() : string
    {

        return '';
    }
}
