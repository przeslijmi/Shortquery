<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql\Queries;

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
use Przeslijmi\Silogger\Log;

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

    /**
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
     * Contents of logic section (WHERE) which was put to this instance to overwrite calculated string.
     *
     * @var string
     */
    private $forcedLogicSection;

    /**
     * Setter for limit.
     *
     * @param integer $sliceFrom   Where from cut the results.
     * @param integer $sliceLength How many records to retur.
     *
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
     * @param mixed ...$fields Array of variables from which Field can be created.
     *
     * @return self
     */
    public function addFields(...$fields) : self
    {

        // Add every field.
        foreach ($fields as $field) {
            $this->addField($field, true);
        }

        return $this;
    }

    /**
     * Adder for one Field with option to add aggregation also.
     *
     * @param mixed   $field     Field to be added.
     * @param boolean $toSelect  Optional, true. If this Field is meant to be in SELECT section of Query.
     * @param boolean $toOrderBy Optional, false. If this Func is meant to be in ORDER BY section of Query.
     * @param boolean $toGroupBy Optional, false. If this Field is meant to be in GROUP BY section of Query.
     *
     * @return Field
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
     * @param string  $funcName  Name of func to be added.
     * @param array   $funcItems Items (words) of func to be added.
     * @param boolean $toSelect  Optional, true. If this Func is meant to be in SELECT section of Query.
     * @param boolean $toOrderBy Optional, false. If this Func is meant to be in ORDER BY section of Query.
     * @param boolean $toGroupBy Optional, false. If this Func is meant to be in GROUP BY section of Query.
     *
     * @return Func
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
     * @param string[]|Val[] ...$vals Array of variables from which Val can be created.
     *
     * @return self
     */
    public function addVals(...$vals) : self
    {

        foreach ($vals as $val) {
            if (is_array($val) === true) {
                $this->select[] = Val::factory($val[0], $val[1]);
            } else {
                $this->select[] = Val::factory($val);
            }
        }

        return $this;
    }

    /**
     * Adder for one Val.
     *
     * @param string|Val $val Val to be added.
     *
     * @return Val
     */
    public function addVal($val) : Val
    {

        // Lvd.
        $val = Val::factory($val);

        // Add to select.
        $this->select[] = $val;

        return $val;
    }

    /**
     * Adder for one Relation.
     *
     * @param string $name Name of relation to be added to be added.
     *
     * @return self
     */
    public function addRelation(string $name) : self
    {

        $this->relations[] = $this->getModel()->getRelationByName($name);

        return $this;
    }

    /**
     * Force use of this WHERE (logic) section instead of caluclating one from fields.
     *
     * @param string $logicSection Logiv section to use.
     *
     * @return self
     */
    public function overwriteLogicSection(string $logicSection): self
    {

        $this->forcedLogicSection = $logicSection;

        return $this;
    }

    /**
     * Converts ContentItems from SELECT section into final SELECT section string.
     *
     * @return string
     */
    private function selectSectionToString() : string
    {

        // Shortcut - nothing is given - and there is no relation - we can use asterix.
        if (count($this->select) === 0 && count($this->relations) === 0) {
            return '*';
        }

        // Lvd.
        $result = [];

        // Nothing is given but there is a relation set - has to be more specific.
        if (count($this->select) === 0 && count($this->relations) > 0) {

            // Add fields from main model - without table prefix.
            foreach ($this->getModel()->getFields() as $field) {

                // Lvd.
                $fullField  = '`' . $this->getModel()->getName() . '`.`' . $field->getName();
                $fullField .= '` AS \'' . $field->getName() . '\'';

                // Add.
                $result[] = $fullField;
            }

            // Add fields from relations - with table prefix.
            foreach ($this->relations as $relation) {

                // Find second model (it can be `to` or `from`).
                $secondModel = $relation->getModelOtherThan($this->getModel()->getName());

                // Add every field.
                foreach ($secondModel->getFields() as $field) {

                    // Lvd.
                    $fullField  = '`' . $secondModel->getName() . '`.`' . $field->getName();
                    $fullField .= '` AS \'' . $secondModel->getName() . '.' . $field->getName() . '\'';

                    // Add.
                    $result[] = $fullField;
                }
            }
        }//end if

        // Finally there are exact instructions on what to add.
        if (count($this->select) > 0) {

            // For every Content Item.
            foreach ($this->select as $contentItem) {
                $result[] = ToString::convert($contentItem);
            }
        }

        return implode(', ', $result);
    }

    /**
     * Converts Relations into final FROM section string.
     *
     * @return string
     */
    private function fromSectionToString() : string
    {

        // Create without Relations.
        $result   = [];
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
     * @return string
     */
    private function logicsSectionToString() : string
    {

        // Use forced (if defined) or calculated.
        if (empty($this->forcedLogicSection) === false) {
            $result = $this->forcedLogicSection;
        } else {
            $result = trim(ToString::convert($this->getLogicsSet()));
        }

        return ( ( $result === '' ) ? '' : 'WHERE ' . $result );
    }

    /**
     * Converts defined limits into final LIMIT section string.
     *
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
            $result[] = ToString::convert($contentItem, 'group');
        }

        return 'GROUP BY ' . implode(', ', $result);
    }

    /**
     * Converts object settings into final ORDER BY section string.
     *
     * @return string
     */
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
            $result[] = ToString::convert($contentItem, 'order');
        }

        return 'ORDER BY ' . implode(', ', $result);
    }

    /**
     * Converts SELECT query into string.
     *
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

        return implode(' ', $result) . ';';
    }

    /**
     * Return records in a simple array.
     *
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

    /**
     * Read records into simple array using `$field` as a key to this array.
     *
     * @param string  $field  Name of field to be used as a key.
     * @param boolean $silent Optional, false. Set to true to ignore errors.
     *
     * @return array
     */
    public function readBy(string $field, bool $silent = false) : array
    {

        // Lvd.
        $array = [];

        // Get results.
        $result = $this->call();

        // Go through every record and put it into final array.
        while (( $record = $result->fetch_assoc() ) !== null) {

            // Fire warning if there are duplicates.
            if ($silent === false && isset($array[$record[$field]]) === true) {

                // Lvd.
                $warning  = 'Query ' . $this->toString() . ' is read by field `' . $field . '` but there is a ';
                $warning .= 'duplicate record on key `' . $record[$field] . '`. Continuing work - but check it.';

                // Log.
                Log::get()->warning($warning);
            }

            // Add to set.
            $array[$record[$field]] = $record;
        }

        return $array;
    }

    /**
     * Read records into multiple array using `$field` as a key to this array.
     *
     * @param string $field Name of field to be used as a key.
     *
     * @return array
     */
    public function readMultipleBy(string $field) : array
    {

        // Lvd.
        $array = [];

        // Get results.
        $result = $this->call();

        // Go through every record and put it into final array.
        while (( $record = $result->fetch_assoc() ) !== null) {
            $array[$record[$field]][] = $record;
        }

        return $array;
    }

    /**
     * Read first found record into given Instance.
     *
     * @param Instance $instance Instance to put found values to.
     *
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
    }

    /**
     * Read all found records into given Collection (replace knonw primary keys and add new).
     *
     * @param Collection $collection Collection to put found values to.
     *
     * @return void
     */
    public function readIntoCollection(Collection $collection) : void
    {

        // Get results.
        $result = $this->call();

        // Get needed Model properties.
        $pkFieldName   = $collection->getModel()->getPkField()->getName();
        $instanceClass = $collection->getModel()->getClass('instanceClass');

        // Lvd.
        $toBePut = [];
        $i       = 0;

        // Scan through every record.
        while (( $array = $result->fetch_assoc() ) !== null) {

            // Check if this record is already present in Collection (by primary key value).
            $pkValue  = $array[$pkFieldName];
            $instance = $collection->getByPk($pkValue);

            // If it is null - it is not found inside Collection.
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
        }//end while

        // So finally - if there are ready Instances to be put to Collection - do it now.
        if (count($toBePut) > 0) {
            $collection->put($toBePut);
        }
    }

    /**
     * Call query and wait for response.
     *
     * @return boolean|mysqli_result
     */
    public function call()
    {

        return $this->engineCallQuery();
    }

    /**
     * Call query without waiting for any response.
     *
     * @return boolean True.
     */
    public function fire() : bool
    {

        return $this->engineFireQuery();
    }
}
