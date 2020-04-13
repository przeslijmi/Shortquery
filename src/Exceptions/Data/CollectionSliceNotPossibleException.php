<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Data;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Data\Collection;

/**
 * Collection has not enough records to make an ordered slice.
 */
class CollectionSliceNotPossibleException extends ClassFopException
{

    /**
     * Constructor.
     *
     * @param Collection   $collection  Collection that has the problem.
     * @param null|integer $sliceFrom   Starting point of silce (first record, starting with 0).
     * @param null|integer $sliceLength Lenght of silce.
     */
    public function __construct(
        Collection $collection,
        ?int $sliceFrom = null,
        ?int $sliceLength = null
    ) {

        // Lvd.
        $sliceFrom   = (int) $sliceFrom;
        $sliceLength = (int) $sliceLength;

        // Define hint.
        $hint  = 'Collection is too short to perform slice. It has only ' . $collection->length() . ' records';
        $hint .= ', and user asked for slice starting from ' . $sliceFrom . ' with a length of ' . $sliceLength . '.';

        // Define.
        $this->addInfo('context', 'slicingCollection');
        $this->addInfo('modelName', $collection->getModel()->getName());
        $this->addInfo('sliceFrom', (string) ( (int) $sliceFrom ));
        $this->addInfo('sliceLength', (string) ( (int) $sliceLength ));
        $this->addHint($hint);
    }
}
