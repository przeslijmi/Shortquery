<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Items;

use Throwable;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Shortquery\Data\Instance;

/**
 * Shoq field while trying to get value of dict of Field. Maybe Field, dictionary or value are wrong?
 */
class FieldDictValueUnaccesibleException extends MethodFopException
{

    /**
     * Constructor.
     *
     * @param string         $fieldName Name of field.
     * @param string         $dictName  Name of dictionary.
     * @param string         $value     Value in this dictionary.
     * @param Instance       $instance  Where to look for fields.
     * @param null|Throwable $cause     Throwable that caused the problem.
     */
    public function __construct(
        string $fieldName,
        string $dictName,
        string $value,
        Instance $instance,
        ?Throwable $cause = null
    ) {

        $this->addInfo('context', 'readingShortqueryFieldDictionaryValue');
        $this->addInfo('model', get_class($instance));
        $this->addInfo('fieldName', $fieldName);
        $this->addInfo('dictName', $dictName);
        $this->addInfo('value', $value);
        $this->addHint('Somehow failed to reach Field dictionary value. See cause.');

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
