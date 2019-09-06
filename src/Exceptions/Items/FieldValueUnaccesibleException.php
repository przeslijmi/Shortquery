<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Shortquery\Data\Instance;

/**
 * Shoq field while trying to get value of Field. Maybe Field is not present?
 */
class FieldValueUnaccesibleException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string         $fieldName Name of field.
     * @param Instance       $instance  Where to look for fields.
     * @param Throwable|null $cause     Throwable that caused the problem.
     *
     * @since v1.0
     *
     * phpcs:disable Generic.Files.LineLength
     */
    public function __construct(string $fieldName, Instance $instance, ?Throwable $cause = null)
    {

        $this->addInfo('context', 'readingShortqueryField');
        $this->addInfo('model', get_class($instance));
        $this->addInfo('fieldName', $fieldName);
        $this->addHint('Somehow failed to reach Field value. See cause.');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
