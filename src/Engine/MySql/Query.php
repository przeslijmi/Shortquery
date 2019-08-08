<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Engine\MySql;

use Przeslijmi\Shortquery\Data\Instance;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Engine\MySql;
use Przeslijmi\Shortquery\Items\LogicItem;
use Przeslijmi\Shortquery\Items\LogicAnd;
use Przeslijmi\Shortquery\Items\LogicOr;
use Przeslijmi\Shortquery\Items\Rule;
use stdClass;

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
     * Instances to use in this query (if applicable).
     *
     * @var Instance[]
     */
    private $instances = [];
    /**
     * Constructor.
     *
     * @param Model $model Model to create query for.
     *
     * @since v1.0
     */
    public function __construct(Model $model)
    {

        $this->model = $model;
    }

    /**
     * Getter for Model.
     *
     * @since  v1.0
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
     * @since  v1.0
     * @return self
     */
    public function setLogicsSet(array $logicsSet) : self
    {

        $this->logicsSet = $logicsSet;

        return $this;
    }

    public function addLogics(LogicItem ...$logics) : self
    {

        $this->logicsSet = array_merge($this->logicsSet, $logics);

        return $this;
    }

    public function addRule() : self
    {

        try {
            $rule = Rule::factory(...func_get_args());
        } catch (Exception $e) {
            throw ( new MethodFopException('creationOfRuleFailed', $e) )
                ->addInfos(func_get_args(), 'ruleArgs');
        }

        $this->addLogics(new LogicAnd($rule));

        return $this;
    }

    /**
     * Getter for logics set.
     *
     * @since  v1.0
     * @return LogicItem[]
     */
    public function getLogicsSet() : array
    {

        return $this->logicsSet;
    }

    /**
     * Clear array of instances to empty.
     *
     * @since  v1.0
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
     * @param Instance $instance Instance to use in this query (if applicable).
     *
     * @since  v1.0
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
     * @param array $instances Instances to use in this query (if applicable).
     *
     * @since  v1.0
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
     * @param Instance $instance Instance to use in this query (if applicable).
     *
     * @since  v1.0
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
     * @param array $instances Instances to use in this query (if applicable).
     *
     * @since  v1.0
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

    protected function valueify($value) : string
    {


        if (is_null($value) === true) {
            $value = 'NULL';

        } elseif (is_string($value) === true) {
            $value = "'" . addslashes($value) . "'";

        } elseif (is_bool($value) === true) {
            $value = (int) $value;

        } elseif (is_scalar($value) === true) {
            $value = str_replace(',', '.', $value);

        } elseif (is_a($value, 'stdClass') === true) {
            $value = "'" . json_encode($value) . "'";

        } else {
            // @todo make throw instead of this
            die('jdfgoijaf3498afjw9qjg54');
        }

        return $value;
    }
}
