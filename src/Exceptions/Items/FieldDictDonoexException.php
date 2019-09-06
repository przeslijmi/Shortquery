<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Shortquery\Data\Field;

/**
 * Shoq Field does not have dictionary named like this.
 */
class FieldDictDonoexException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string         $dictName Name of dictionary.
     * @param Field          $field    Name of field.
     * @param Throwable|null $cause    Throwable that caused the problem.
     *
     * @since v1.0
     *
     * phpcs:disable Generic.Files.LineLength
     */
    public function __construct(string $dictName, Field $field, ?Throwable $cause = null) {

        $this->addInfo('context', 'readingFieldDictionaries');
        $this->addInfo('field', get_class($field));
        $this->addInfo('fieldName', $field->getName());
        $this->addInfo('dictSearched', $dictName);
        $this->addInfo('dictsPresent', implode(', ', array_keys($field->getDicts())));
        $this->addHint('You\'re trying to get dictionary that does not exists.');

        // Add Model info if reacheable.
        if ($field->hasModel() === true) {
            $this->addInfo('model', get_class($field->getModel()));
            $this->addInfo('modelName', $field->getModel()->getName());
        }

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
