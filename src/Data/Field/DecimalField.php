<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Field;

use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Data\FieldInterface;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Exceptions\Items\FieldDefinitionWrosynException;
use Przeslijmi\Shortquery\Exceptions\Items\FieldValueInproperException;

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
    private $maxLength = 15;

    /**
     * Number of decimal digits for value in Field.
     *
     * @var integer
     */
    private $fractionDigits = 2;

    /**
     * Constructor.
     *
     * @param string  $name    Name of Field.
     * @param boolean $notNull Opt., false. If true - null value is not accepted.
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
        $this->setPhpDocsType('float');
    }

    /**
     * Setter for `maxLength` and `fractionDigits`.
     *
     * @param integer $maxLength      Maximum length of value in Field.
     * @param integer $fractionDigits Number of decimal digits for value in Field.
     *
     * @return self
     */
    public function setSize(int $maxLength, int $fractionDigits = 2) : self
    {

        $this->setMaxLength($maxLength);
        $this->setFractionDigits($fractionDigits);

        return $this;
    }

    /**
     * Setter for `maxLength`.
     *
     * @param integer $maxLength Maximum length of value in Field.
     *
     * @throws FieldDefinitionWrosynException When max length is below 1 or above 21.
     * @return self
     */
    public function setMaxLength(int $maxLength) : self
    {

        // On too low.
        if ($maxLength < 1) {
            throw new FieldDefinitionWrosynException(
                [ 'Max lenght can not be lower than 1.', get_class($this), $this->getName() ]
            );
        }

        // On too high.
        if ($maxLength > 21) {
            throw new FieldDefinitionWrosynException(
                [ 'Max lenght can not be greater than 21.', get_class($this), $this->getName() ]
            );
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
     * @throws FieldDefinitionWrosynException When there are too many or too little fraction digits.
     * @return self
     */
    public function setFractionDigits(int $fractionDigits) : self
    {

        // On too low.
        if ($fractionDigits < 1) {
            throw new FieldDefinitionWrosynException(
                [ 'Number of fraction digits can not be lower than 1.', get_class($this), $this->getName() ]
            );
        }

        // On too high.
        if ($fractionDigits > ( $this->maxLength - 1 )) {

            // Lvd.
            $info  = 'Number of fraction digits have to be included in max length of field ';
            $info .= '(eg. 4.5331 is number with max lenght 5, and 4 fraction digits.';

            throw new FieldDefinitionWrosynException([ $info, get_class($this), $this->getName() ]);
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
     * @param null|integer $value  Value to be checked.
     * @param boolean      $throws Optional, true. If set to false `throw` will be off.
     *
     * @throws FieldValueInproperException When Field value is inproper.
     * @return boolean
     */
    public function isValueValid(?float $value, bool $throws = true) : bool
    {

        // Lvd.
        $result = true;

        // If null - it is valid.
        if (is_null($value) === true) {
            return $result;
        }

        // Checks max length.
        if (mb_strlen(preg_replace('/([^0-9])/', '', $value)) > $this->maxLength) {
            $result = false;
        }

        // Check fraction length.
        if ($result === true && strpos((string) $value, '.') !== false) {

            // Lvd.
            list($integer, $fraction) = explode('.', (string) $value);

            // Test.
            if (mb_strlen($fraction) > $this->fractionDigits) {
                $result = false;
            }
        }

        // Throw.
        if ($result === false && $throws === true) {

            // Prepare info.
            $info = [
                $this->getProperValueHint(),
                get_class($this),
                $this->getName(),
                (string) $value,
            ];
            if ($this->hasModel() === true) {
                $info['model']     = get_class($this->getModel());
                $info['modelName'] = $this->getModel()->getName();
            }

            // Throw.
            throw new FieldValueInproperException($info);
        }

        return $result;
    }

    /**
     * Prepare PHP commands to create this Field in model.
     *
     * @return string
     */
    public function toPhp() : string
    {

        // Result.
        $php  = $this->ln(0, '', 1);
        $php .= $this->ln(
            3,
            '( new DecimalField(\'' . $this->getName() . '\', ' . $this->ex($this->isNotNull()) . ') )'
        );
        $php .= $this->ln(4, '->setSize(' . $this->getMaxLength() . ', ' . $this->getFractionDigits() . ')');
        $php .= $this->ln(4, '->setPk(' . $this->ex($this->isPrimaryKey()) . ')', 1);
        $php .= $this->ln(2, '', 0);

        return $php;
    }

    /**
     * Prepare PHP commands for getter.
     *
     * @return string
     */
    public function getterToPhp() : string
    {

        return $this->ln(2, 'return ' . $this->cc(true) . ';');
    }

    /**
     * Prepare PHP commands for comparer given value vs saved value.
     *
     * @return string
     */
    public function compareToPhp() : string
    {

        $php  = $this->ln(0, 'if (');
        $php .= $this->ln(3, $this->cc(true) . ' === $' . $this->cc() . '');
        $php .= $this->ln(3, '|| (string) ' . $this->cc(true) . ' === (string) $' . $this->cc() . '');
        $php .= $this->ln(2, ') {');

        return $php;
    }

    /**
     * Prepare PHP commands for additional, extra methods to put inside generated Field class.
     *
     * @param Model $model To use for PHP code.
     *
     * @return string
     */
    public function extraMethodsToPhp(Model $model) : string
    {

        return '';
    }

    /**
     * Deliver hint for value correctness for this Field.
     *
     * @return string
     */
    public function getProperValueHint() : string
    {

        // Lvd.
        $result  = 'There has to be no more than ' . $this->getFractionDigits() . ' fraction digits ';
        $result .= 'and whole value has to be shorter than ' . $this->getMaxLength() . ' digits.';

        return $result;
    }
}
