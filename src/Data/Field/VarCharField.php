<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data\Field;

use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Data\FieldInterface;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Exceptions\Items\FieldDefinitionWrosynException;
use Przeslijmi\Shortquery\Exceptions\Items\FieldValueInproperException;

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
     * @param string  $name    Name of Field.
     * @param boolean $notNull Opt., false. If true - null value is not accepted.
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
        $this->setPhpDocsType('string');
    }

    /**
     * Setter for `maxLength`.
     *
     * @param integer $maxLength Maximum length of the value in Field.
     *
     * @throws FieldDefinitionWrosynException When max length is below 1 or above 65000.
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
        if ($maxLength > 65000) {
            throw new FieldDefinitionWrosynException(
                [ 'Max lenght can not be greater than 65000.', get_class($this), $this->getName() ]
            );
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
     * @param null|integer $value  Value to be checked.
     * @param boolean      $throws Optional, true. If set to false `throw` will be off.
     *
     * @throws FieldValueInproperException When Field value is inproper.
     * @return boolean
     */
    public function isValueValid(?string $value, bool $throws = true) : bool
    {

        // Lvd.
        $result = true;

        // If null - it is valid.
        if (is_null($value) === true) {
            return $result;
        }

        // Checks.
        if (mb_strlen($value) > $this->maxLength) {
            $result = false;
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
            '( new VarCharField(\'' . $this->getName() . '\', ' . $this->ex($this->isNotNull()) . ') )'
        );
        $php .= $this->ln(4, '->setMaxLength(' . $this->getMaxLength() . ')');
        $php .= $this->ln(4, '->setPk(' . $this->ex($this->isPrimaryKey()) . ')');
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

        return $this->ln(0, 'if (' . $this->cc(true) . ' === $' . $this->cc() . ') {');
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

        return 'It has to be an string not longer than ' . $this->getMaxLength() . ' chars.';
    }
}
