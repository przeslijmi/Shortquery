<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Field;

use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Data\FieldInterface;

/**
 * Most standard Field to use - varchar.
 */
class VarCharField extends Field implements FieldInterface
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
        $this->setMaxLength(45);
        $this->setType('VarCharField');
        $this->setEngineType('varchar');
        $this->setPhpType('string');
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
            die('siaofj49fjwadw');
        }

        // On too high.
        if ($maxLength > 8000) {
            die('siaofj49fjwadw8');
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
     * @param string $value Value to be checked.
     *
     * @since  v1.0
     * @return boolean
     */
    public function isValueValid(?string $value) : bool
    {

        // If null - it is valid.
        if (is_null($value) === true) {
            return true;
        }

        // Checks.
        if (mb_strlen($value) > $this->maxLength) {
            die('VarCharField inproper value too long (' . mb_strlen($value) . ' vs ' . $this->maxLength . ') ' . $value . '');
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
        $result .= str_repeat($indent, 3) . '(new VarCharField(\'' . $this->getName() . '\', ';
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
