<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\Queries;

use Exception;
use mysqli_result;
use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Shortquery\Data\Instance;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Engine\MySql\Query;
use Przeslijmi\Shortquery\Engine\MySql\ToString;
use Przeslijmi\Shortquery\Items\Field;
use Przeslijmi\Shortquery\Items\Func;
use Przeslijmi\Shortquery\Items\Val;
use Przeslijmi\Shortquery\Tools\InstancesFactory;

/**
 * Tool for creating SELECT query (including its string representation).
 */
class SelectQuery extends Query
{

    /**
     * Where from cut the results (if needed).
     *
     * @var integer
     */
    private $sliceFrom = 0;

    /**
     * How many records to retur (if needed).
     *
     * @var integer
     */
    private $sliceLength = 0;

    /**s
     * Set of ContentItems (Fields, Funcs, Val) to SELECT section.
     *
     * @var ContentItem[]
     */
    private $select = [];

    /**
     * Set of Relations to FROM section.
     *
     * @var Relation[]
     */
    private $relations = [];

    /**
     * Set of ContentItems (Fields, Funcs, Val) to GROUP BY section.
     *
     * @var ContentItem[]
     */
    private $groupBy = [];

    /**
     * Set of ContentItems (Fields, Funcs, Val) to ORDER BY section.
     *
     * @var ContentItem[]
     */
    private $orderBy = [];

    /**
     * Setter for limit.
     *
     * @param integer $sliceFrom   Where from cut the results.
     * @param integer $sliceLength How many records to retur.
     *
     * @since  v1.0
     * @return self
     */
    public function setLimit(int $sliceFrom = 0, int $sliceLength = 1) : self
    {

        // Save.
        $this->sliceFrom   = $sliceFrom;
        $this->sliceLength = $sliceLength;

        return $this;
    }

    /**
     * Adder for Field ContentItems.
     *
     * @param mixed[] ...$fields Array of variables from which Field can be created.
     *
     * @since  v1.0
     * @return void
     */
    public function addFields(...$fields) : self
    {

        foreach ($fields as $field) {
            $this->select[] = Field::factory($field);
        }

        return $this;
    }

    /**
     * Adder for one Field with option to add aggregation also.
     *
     * @param Field   $field     Field to be added.
     * @param boolean $toSelect  Optional, true. If this Field is meant to be in SELECT section of Query.
     * @param boolean $toGroupBy Optional, false. If this Field is meant to be in GROUP BY section of Query.
     *
     * @since  v1.0
     * @return void
     */
    public function addField(
        $field,
        bool $toSelect = true,
        bool $toOrderBy = false,
        bool $toGroupBy = false
    ) : Field {

        // Create 100% real Field.
        $field = Field::factory($field);

        // Use it in SELECT section.
        if ($toSelect === true) {
            $this->select[] = $field;
        }

        // Use it in GROUP BY section.
        if ($toOrderBy === true) {
            $this->orderBy[] = $field;
        }

        // Use it in GROUP BY section.
        if ($toGroupBy === true) {
            $this->groupBy[] = $field;
        }

        return $field;
    }

    /**
     * Adder for one Func with option to add aggregation also.
     *
     * @param Func    $func      Func to be added.
     * @param boolean $toSelect  Optional, true. If this Func is meant to be in SELECT section of Query.
     * @param boolean $toGroupBy Optional, false. If this Func is meant to be in GROUP BY section of Query.
     *
     * @since  v1.0
     * @return void
     */
    public function addFunc(
        string $funcName,
        array $funcItems,
        bool $toSelect = true,
        bool $toOrderBy = false,
        bool $toGroupBy = false
    ) : Func {

        // Create 100% real Func.
        $func = Func::factory($funcName, $funcItems);

        // Use it in content items.
        if ($toSelect === true) {
            $this->select[] = $func;
        }

        // Use it in sorting.
        if ($toOrderBy === true) {
            $this->orderBy[] = $func;
        }

        // Use it in aggregation.
        if ($toGroupBy === true) {
            $this->groupBy[] = $func;
        }

        return $func;
    }

    /**
     * Adder for Val ContentItems.
     *
     * @param Val[] ...$vals Array of variables from which Val can be created.
     *
     * @since  v1.0
     * @return void
     */
    public function addVals(...$vals) : self
    {

        foreach ($vals as $val) {
            $this->select[] = Val::factory($val);
        }

        return $this;
    }

    public function addRelation(string $name) : self
    {

        $this->relations[] = $this->getModel()->getRelationByName($name);

        return $this;
    }

    /**
     * Converts ContentItems from SELECT section into final SELECT section string.
     *
     * @since  v1.0
     * @return string
     */
    private function selectSectionToString() : string
    {

        // Shortcut.
        if (count($this->select) === 0) {
            return '*';
        }

        // Lvd.
        $result = [];

        // For every Content Item.
        foreach ($this->select as $contentItem) {
            $result[] = ToString::toString($contentItem);
        }

        return implode(', ', $result);
    }

    /**
     * Converts Relations into final FROM section string.
     *
     * @since  v1.0
     * @return string
     */
    private function fromSectionToString() : string
    {

        // Create without Relations.
        $result = [];
        $result[] = 'FROM';
        $result[] = '`' . $this->getModel()->getName() . '`';

        // Add Relations.
        foreach ($this->relations as $relation) {

            // Lvd.
            $tableFrom = $relation->getModelFrom()->getName();
            $tableTo   = $relation->getModelTo()->getName();
            $fieldFrom = $relation->getFieldFrom()->getName();
            $fieldTo   = $relation->getFieldTo()->getName();

            // Fill up.
            $syntax  = 'LEFT JOIN `' . $tableTo . '`';
            $syntax .= ' ON `' . $tableFrom . '`.`' . $fieldFrom . '`=`' . $tableTo . '`.`' . $fieldTo . '`';

            // Add.
            $result[] = $syntax;
        }

        return implode(' ', $result);
    }

    /**
     * Converts ContentItems from SELECT section into final SELECT section string.
     *
     * @since  v1.0
     * @return string
     */
    private function logicsSectionToString() : string
    {

        $result = trim(ToString::toString($this->getLogicsSet()));

        return ( ( $result === '' ) ? '' : 'WHERE ' . $result );
    }

    /**
     * Converts defined limits into final LIMIT section string.
     *
     * @since  v1.0
     * @return string
     */
    private function limitSectionToString() : string
    {

        // Prepare string.
        if ($this->sliceFrom > 0 || $this->sliceLength > 0) {
            return 'LIMIT ' . $this->sliceFrom . ', ' . $this->sliceLength;
        }

        return '';
    }

    /**
     * Converts ContentItems from GROUP BY section into final GROUP BY section string.
     *
     * @since  v1.0
     * @return string
     */
    private function groupBySectionToString() : string
    {

        // Shortcut.
        if (count($this->groupBy) === 0) {
            return '';
        }

        // Lvd.
        $result = [];

        // For every Content Item.
        foreach ($this->groupBy as $contentItem) {
            $result[] = ToString::toString($contentItem);
        }

        return 'GROUP BY ' . implode(', ', $result);
    }

    private function orderBySectionToString() : string
    {

        // Shortcut.
        if (count($this->orderBy) === 0) {
            return '';
        }

        // Lvd.
        $result = [];

        // For every Content Item.
        foreach ($this->orderBy as $contentItem) {
            $result[] = ToString::toString($contentItem);
        }

        return 'ORDER BY ' . implode(', ', $result);
    }

    /**
     * Converts SELECT query into string.
     *
     * @since  v1.0
     * @return string
     */
    public function toString() : string
    {

        // Lvd.
        $result = [];

        // Fill up.
        $result[] = 'SELECT';
        $result[] = $this->selectSectionToString();
        $result[] = $this->fromSectionToString();
        $result[] = $this->logicsSectionToString();
        $result[] = $this->groupBySectionToString();
        $result[] = $this->orderBySectionToString();
        $result[] = $this->limitSectionToString();

        // Delete empty sections.
        foreach ($result as $i => $section) {
            if (empty($section) === true) {
                unset($result[$i]);
            }
        }

        // print_r(implode(' ', $result) . ';');
        // die;

        return implode(' ', $result) . ';';
    }

    /**
     * Just return records in a simple array.
     *
     * @since  v1.0
     * @return array
     */
    public function read() : array
    {

        // Lvd.
        $array = [];

        // Get results.
        $result = $this->call();

        // Go through every record and put it into final array.
        while (( $record = $result->fetch_assoc() ) !== null) {
            $array[] = $record;
        }

        return $array;
    }

    public function readBy(string $field) : array
    {

        // Lvd.
        $array = [];

        // Get results.
        $result = $this->call();

        // Go through every record and put it into final array.
        while (( $record = $result->fetch_assoc() ) !== null) {
            $array[$record[$field]] = $record;
        }

        return $array;
    }

    /**
     * Read first found record into given Instance.
     *
     * @param Instance $instance Instance to put found values to.
     *
     * @since  v1.0
     * @return void
     */
    public function readIntoInstance(Instance $instance) : void
    {

        // Get results.
        $result = $this->call();
        $array  = $result->fetch_assoc();

        // If result is proper.
        if (is_array($array) === true) {

            // Use Instance factory to fill Instance.
            InstancesFactory::fromArray($instance, $array);
            $instance->defineIsAdded(true);
            $instance->defineNothingChanged();
        }

        // throw new Exception('nothing has been taken from DB so nothing can be put to Instance');
        return;
    }

    /**
     * Read all found records into given Collection (replace knonw primary keys and add new).
     *
     * @param Collection $collection Collection to put found values to.
     *
     * @since  v1.0
     * @return void
     */
    public function readIntoCollection(Collection $collection) : void
    {

        // Get results.
        $result = $this->call();

        // Get needed Model properties.
        $pkFieldName   = $collection->getModel()->getPkField()->getName();
        $instanceClass = $collection->getModel()->getClass('instanceClass');

        // Lvd
        $toBePut = [];
        $i       = 0;

        // Scan through every record.
        while (( $array = $result->fetch_assoc() ) !== null) {

            // Check if this record is already present in Collection (by primary key value)
            $pkValue  = $array[$pkFieldName];
            $instance = $collection->getByPk($pkValue);

            // If it is null - if it is not found inside Collection.
            if ($instance === null) {

                // Create new Instance.
                $instance = InstancesFactory::fromArray($instanceClass, $array);
                $instance->defineIsAdded(true);
                $instance->defineNothingChanged();

                // And save it for future mass adding (it is not a good option to add it right now because
                // every iteration of this while will be slower with every new record - while checking if primary
                // key exists will last longer becuase more and more new (freshly added records) will be added.
                $toBePut[] = $instance;

                // Do not go any further.
                continue;
            }

            // If it is not null - this primary kay is already present in Collection. Just update.
            InstancesFactory::fromArray($instance, $array);
            $instance->defineIsAdded(true);
            $instance->defineNothingChanged();
        }

        // So finally - if there are ready Instances to be put to Collection - do it now.
        if (count($toBePut) > 0) {
            $collection->put($toBePut);
        }
    }

    public function call() : mysqli_result
    {

        $result = $this->engineCallQuery();

        return $result;
    }

    public function fire() : mysqli_result
    {

        $result = $this->engineFireQuery();

        return $result;
    }
}
