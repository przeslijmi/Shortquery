<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql;

use Przeslijmi\Shortquery\Data\Instance;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Engine\MySql;
use Przeslijmi\Shortquery\Exceptions\Engines\MySql\ValuifyFopException;
use Przeslijmi\Shortquery\Items\LogicAnd;
use Przeslijmi\Shortquery\Items\LogicItem;
use Przeslijmi\Shortquery\Items\LogicOr;
use Przeslijmi\Shortquery\Items\Rule;
use stdClass;

/**
 * Query contructor for MySql.
 */
abstract class Query extends MySql
{

    /**
     * Model to create query for.
     *
     * @var Model
     */
    private $model;

    /**
     * Array of logics to use in this query.
     *
     * @var LogicItem[]
     */
    private $logicsSet = [];

    /**
     * Instances to use in this query.
     *
     * @var Instance[]
     */
    private $instances = [];

    /**
     * Constructor.
     *
     * @param Model  $model    Model to create query for.
     * @param string $database Opt., null. To which database of this model you want a query.
     */
    public function __construct(Model $model, ?string $database = null)
    {

        $this->model    = $model;
        $this->database = ( $database ?? $model->getDatabase() );
    }

    /**
     * Getter for Model.
     *
     * @return Model
     */
    public function getModel() : Model
    {

        return $this->model;
    }

    /**
     * Setter for logics set.
     *
     * @param array $logicsSet Array of logics to use in this query.
     *
     * @return self
     */
    public function setLogicsSet(array $logicsSet) : self
    {

        $this->logicsSet = $logicsSet;

        return $this;
    }

    /**
     * Add logics to query.
     *
     * @param LogicItem ...$logics Logics to be added.
     *
     * @return self
     */
    public function addLogics(LogicItem ...$logics) : self
    {

        $this->logicsSet = array_merge($this->logicsSet, $logics);

        return $this;
    }

    /**
     * Add rule to query (send Rules as params).
     *
     * @return self
     */
    public function addRule() : self
    {

        $this->addLogics(new LogicAnd(Rule::factory(...func_get_args())));

        return $this;
    }

    /**
     * Getter for logics set.
     *
     * @return LogicItem[]
     */
    public function getLogicsSet() : array
    {

        return $this->logicsSet;
    }

    /**
     * Clear array of instances to empty.
     *
     * @return self
     */
    public function clearInstances() : self
    {

        $this->instances = [];

        return $this;
    }

    /**
     * Setter for one Instance.
     *
     * @param Instance $instance Instance to use in this query.
     *
     * @return self
     */
    public function setInstance(Instance $instance) : self
    {

        $this->instances = [ $instance ];

        return $this;
    }

    /**
     * Setter for Instances.
     *
     * @param array $instances Instances to use in this query.
     *
     * @return self
     */
    public function setInstances(array $instances) : self
    {

        $this->instances = $instances;

        return $this;
    }

    /**
     * Adder for one Instance (increasing collection).
     *
     * @param Instance $instance Instance to use in this query.
     *
     * @return self
     */
    public function addInstance(Instance $instance) : self
    {

        $this->instances[] = $instance;

        return $this;
    }

    /**
     * Adder for Instances (increasing collection).
     *
     * @param array $instances Instances to use in this query.
     *
     * @return self
     */
    public function addInstances(array $instances) : self
    {

        $this->instances = array_merge($this->instances, $instances);

        return $this;
    }

    /**
     * Getter for Instances.
     *
     * @return Instance[]
     */
    public function getInstances() : array
    {

        return $this->instances;
    }

    /**
     * Converts php value into proper syntax mysql query value.
     *
     * @param mixed $value Value to be converted to value in MySQL query.
     *
     * @throws ValuifyFopException When can't valuify this value.
     * @return string
     */
    public function valueify($value) : string
    {

        // Make job done.
        if (is_null($value) === true) {
            return 'NULL';
        } elseif (is_string($value) === true) {
            return "'" . addslashes($value) . "'";
        } elseif (is_bool($value) === true) {
            return (string) (int) $value;
        } elseif (is_scalar($value) === true) {
            return str_replace(',', '.', (string) $value);
        } elseif (is_a($value, 'stdClass') === true) {
            return "'" . json_encode($value) . "'";
        }

        // Throw.
        throw new ValuifyFopException([ var_export($value, true) ]);
    }
}
