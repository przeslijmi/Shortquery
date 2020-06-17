<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Creator;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Creator has to take schema from one of two sources.
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class SchemaMissingException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Creator has to take schema from one of two sources: a file given as param -su (--schemaUri) or as direct injection `->setParam(\'schema\')`. None of that happend.';
}
