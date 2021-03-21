<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Model;

use Przeslijmi\Sexceptions\Sexception;

/**
 * You\'re trying to add next Relation to the model - but the name is already taken by another Relation
 * in this Model. Model can\'t have two or more Relations with the same name.
 *
 * @phpcs:disable Generic.Files.LineLength
 */
class ModelRelationNameAlrexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'You\'re trying to add next Relation to the model - but the name is already taken by another Relation in this Model. Model can\'t have two or more Relations with the same name.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'modelName',
        'modelClass',
        'relationName',
    ];
}
