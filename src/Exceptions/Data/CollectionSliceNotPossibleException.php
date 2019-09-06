<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Exceptions\Data;

use Exception;
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
     * @param Collection     $collection Collection that has the problem.
     * @param Exception|null $cause      Exception that caused the problem.
     *
     * @since v1.0
     */
    public function __construct(
        Collection $collection,
        ?int $sliceFrom = null,
        ?int $sliceLength = null,
        ?Exception $cause = null
    ) {

        // Lvd.
        $sliceFrom   = (int) $sliceFrom;
        $sliceLength = (int) $sliceLength;

        // Define hint.
        $hint  = 'Collection is too short to perform slice. It has only ' . $collection->length() . ' records';
        $hint .= ', and user asked for slice starting from ' . $sliceFrom . ' with a length of ' . $sliceLength . '.';

        // Define.
        $this->setCodeName('CollectionSliceNotPossibleException');
        $this->addInfo('context', 'slicingCollection');
        $this->addInfo('modelName', $collection->getModel()->getName());
        $this->addInfo('sliceFrom', (string) ( (int) $sliceFrom ));
        $this->addInfo('sliceLength', (string) ( (int) $sliceLength ));
        $this->addInfo('hint', $hint);

        if (is_null($cause) === false) {
            $this->setCause($cause);
        }
    }
}
