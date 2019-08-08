<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Field;

use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Data\FieldInterface;

/**
 * Field to use - decimal.
 */
class DecimalField extends Field implements FieldInterface
{

    /**
     * Maximum length of value in Field.
     *
     * @var integer
     */
    private $maxLength;

    /**
     * Number of decimal digits for value in Field.
     *
     * @var integer
     */
    private $fractionDigits;

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
        $this->setMaxLength(15);
        $this->setFractionDigits(2);
        $this->setType('DecimalField');
        $this->setEngineType('decimal');
        $this->setPhpType('float');
    }

    public function setSize(int $maxLength, int $fractionDigits = 2)
    {

        $this->setMaxLength($maxLength);
        $this->setFractionDigits($fractionDigits);

        return $this;
    }

    /**
     * Setter for `maxLength`.
     *
     * @param integer $maxLength     Maximum length of value in Field.
     *
     * @since  v1.0
     * @return self
     */
    public function setMaxLength(int $maxLength) : self
    {

        // On too low.
        if ($maxLength < 1) {
            die('siaofj49fjwaefresfdwfdsrefer');
        }

        // On too high.
        if ($maxLength > 21) {
            die('siaofj49fjwasdcsfdwerfrefer');
        }

        // Save.
        $this->maxLength = $maxLength;

        return $this;
    }

    /**
     * Setter for `fractionDigits`.
     *
     * @param integer $fractionDigits Number of decimal digits for value in Field.
     *
     * @since  v1.0
     * @return self
     */
    public function setFractionDigits(int $fractionDigits) : self
    {

        // On too low.
        if ($fractionDigits < 1) {
            die('siaofj49fjwadwfgsergsergfdsrefer');
        }

        // On too high.
        if ($fractionDigits > ( $this->maxLength - 1 )) {
            die('siaofj49fjergrserwadwerfrefer');
        }

        // Save.
        $this->fractionDigits = $fractionDigits;

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
     * Getter for `fractionDigits`.
     *
     * @return integer
     */
    public function getFractionDigits() : int
    {

        return $this->fractionDigits;
    }

    /**
     * Checks if value of Field is valid according to this type.
     *
     * @param null|int $value Value to be checked.
     *
     * @since  v1.0
     * @return boolean
     */
    public function isValueValid(?float $value) : bool
    {

        // If null - it is valid.
        if (is_null($value) === true) {
            return true;
        }


        // Checks max length.
        if (mb_strlen(preg_replace('/([^0-9])/', '', $value)) > $this->maxLength) {
            die('DecimalField inproper value too long ' . $value . '');
        }

        if (strpos((string) $value, '.') !== false) {
            list($integer, $fraction) = explode('.', (string) $value);

            // if (mb_strlen($fraction) > $this->fractionDigits) {
            //     $hint  = 'DecimalField inproper fraction part too long ' . $value;
            //     $hint .= ' (max ' . $this->fractionDigits . ')';
            //     throw new \Exception($hint);
            // }
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

        // Result.
        $php  = PHP_EOL;
        $php .= $this->ln(3, '(new DecimalField(\'' . $this->getName() . '\', ' . $this->ex($this->isNotNull()) . '))');
        $php .= $this->ln(4, '->setSize(' . $this->getMaxLength() . ', ' . $this->getFractionDigits() . ')');
        $php .= $this->ln(4, '->setPk(' . $this->ex($this->isPrimaryKey()) . ')', 2);

        return $php;
    }

    public function getterToPhp() : string
    {

        return $this->ln(2, 'return $this->' . $this->getName('camelCase') . ';');
    }

    public function compareToPhp() : string
    {

        // Lvd.
        $cc = $this->getName('camelCase');

        $php  = '';
        $php .= $this->ln(0, 'if (');
        $php .= $this->ln(3, '$this->' . $cc . ' === $' . $cc . '');
        $php .= $this->ln(3, '|| (string) $this->' . $cc . ' === (string) $' . $cc . '');
        $php .= $this->ln(2, ') {');

        return $php;
    }

    public function extraMethodsToPhp() : string
    {

        return '';
    }
}
