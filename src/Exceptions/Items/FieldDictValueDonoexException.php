<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Shortquery\Data\Field;

/**
 * Shoq field has a dict in which there is no key like this.
 */
class FieldDictValueDonoexException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string         $dictName Name of dictionary.
     * @param string         $value    Value in this dictionary.
     * @param Field          $field    Field itself.
     * @param Throwable|null $cause    Throwable that caused the problem.
     *
     * @since v1.0
     *
     * phpcs:disable Generic.Files.LineLength
     */
    public function __construct(
        string $dictName,
        string $value,
        Field $field,
        ?Throwable $cause = null
    ) {

        $this->addInfo('context', 'readingShortqueryFieldDictionaryValue');
        $this->addInfo('field', get_class($field));
        $this->addInfo('fieldName', $field->getName());
        $this->addInfo('dictName', $dictName);
        $this->addInfo('valueSearched', $value);
        $this->addInfo('valuesPresent', implode(', ', $field->getValues($dictName)));
        $this->addHint('There is no given key in this dictionary.');

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