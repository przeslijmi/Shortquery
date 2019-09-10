<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use Throwable;
use Przeslijmi\Shortquery\Engine;
use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Data\Relation;
use Przeslijmi\Shortquery\Exceptions\Model\ModelCollectionClassNameDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelCollectionClassNameWrosynException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelDatabaseDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelDatabaseNameOtosetException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelEngineDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelFieldDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelFieldNameAlrexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelInstanceClassNameDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelInstanceClassNameWrosynException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelNameDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelNamespaceDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelNamespaceWrosynException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelNameWrosynException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelQueryCreationFailedException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelPrimaryKeyFieldDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelPrimaryKeyFieldAlrexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelRelationNameAlrexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelRelationDonoexException;
use Przeslijmi\Sivalidator\RegEx;
use Przeslijmi\Sexceptions\Exceptions\ParamOtosetException;

/**
 * Child class of Collection containing Model related information.
 */
class Model
{

    /**
     * Name of Model used by engine (eg. for MySql it'll be a table name).
     *
     * @var string
     */
    private $name;

    /**
     * Name of databases in witch Model is present (eg. mysql/localhost/user/password/port).
     *
     * @var string[]
     */
    private $databases = [];

    /**
     * Namespace of instance class (without instance class itself) (eg. Vendor/App/Models).
     *
     * @var string
     */
    private $namespace;

    /**
     * Name of class (without namespace) that instances records (eg. Car).
     *
     * @var string
     */
    private $instanceClassName;

    /**
     * Name of class (without namespace) that instances collection of records (eg. Cars).
     *
     * @var string
     */
    private $collectionClassName;

    /**
     * Collection of Fields.
     *
     * @var Field[]
     */
    private $fields = [];

    /**
     * Collection of relations.
     *
     * @var Relation[]
     */
    private $relations = [];

    /**
     * Setter for name.
     *
     * @param string $name Collection name.
     *
     * @since  v1.0
     * @throws ModelNameWrosynException When Model name is wrong.
     * @return self
     */
    public function setName(string $name) : self
    {

        // If name is proper.
        try {
            RegEx::ifMatches($name, '/^([a-zA-Z_])+([a-zA-Z0-9_])*$/');
        } catch (Throwable $thr) {
            throw new ModelNameWrosynException($name, $this, $thr);
        }

        // Save.
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for name.
     *
     * @since  v1.0
     * @throws ModelNameDonoexException When name is empty (null).
     * @return string
     */
    public function getName() : string
    {

        // Throw on null.
        if (is_null($this->name) === true) {
            throw new ModelNameDonoexException($this);
        }

        return $this->name;
    }

    /**
     * Setter for database name.
     *
     * @param string $database Engine name.
     *
     * @since  v1.0
     * @throws ModelDatabaseNameOtosetException When Model database name is wrong.
     * @return self
     */
    public function setDatabases(string $database) : self
    {

        // If any database is not proper.
        foreach (func_get_args() as $database) {
            if (isset(PRZESLIJMI_SHORTQUERY_DATABASES[$database]) === false) {
                throw new ModelDatabaseNameOtosetException($database, $this);
            }
        }

        // Save.
        $this->databases = func_get_args();

        return $this;
    }

    /**
     * Getter for databases.
     *
     * @since  v1.0
     * @return string[]
     */
    public function getDatabases() : array
    {

        return $this->databases;
    }

    /**
     * Getter for databases as string (format: `'database1', 'database2'`).
     *
     * @since  v1.0
     * @return string
     */
    public function getDatabasesAsString() : string
    {

        return '\'' . implode('\', \'', $this->databases) . '\'';
    }

    /**
     * Getter for databases.
     *
     * @since  v1.0
     * @throws ModelDatabaseDonoexException When no database is defined for this model.
     * @return string
     */
    public function getDatabase(string $database = null) : string
    {

        // Check if given database is accepted.
        if ($database !== null && in_array($database, $this->databases) === true) {
            return $database;
        }

        // Find which database.
        if ($database === null && isset($this->databases[0]) === true) {
            return $this->databases[0];
        }

        throw new ModelDatabaseDonoexException($this);
    }

    /**
     * Getter for engine name.
     *
     * @since  v1.0
     * @throws ModelEngineDonoexException   When there is no engine for this database.
     * @return string
     */
    public function getEngine(?string $database = null) : string
    {

        // Lvd.
        $engine = '';

        // Get result.
        try {

            // Get database.
            $database = PRZESLIJMI_SHORTQUERY_DATABASES[$this->getDatabase($database)];

            // Get engine.
            $engine = $database['engine'];

        } catch (Throwable $thr) {
            throw new ModelEngineDonoexException($this, $thr);
        }

        return $engine;
    }

    /**
     * Returns Select Query object for this model.
     *
     * @param string $database Opt., null. To which database of this model you want a query?
     *
     * @since  v1.0
     * @return SelectQuery
     */
    public function newSelect(?string $database = null) : Engine
    {

        // Lvd.
        $query = null;

        try {

            // Lvd.
            $database   = $this->getDatabase($database);
            $engine     = PRZESLIJMI_SHORTQUERY_ENGINES[$this->getEngine($database)];
            $queryClass = $engine['readQuery'];

            // Create query.
            $query = new $queryClass($this, $database);

        } catch (Throwable $thr) {
            throw new ModelQueryCreationFailedException($this, 'SELECT', $thr);
        }

        return $query;
    }

    /**
     * Returns Update Query object for this model.
     *
     * @param string $database Opt., null. To which database of this model you want a query?
     *
     * @since  v1.0
     * @return UpdateQuery
     */
    public function newUpdate(?string $database = null) : Engine
    {

        // Lvd.
        $query = null;

        try {

            // Lvd.
            $database   = $this->getDatabase($database);
            $engine     = PRZESLIJMI_SHORTQUERY_ENGINES[$this->getEngine($database)];
            $queryClass = $engine['updateQuery'];

            // Create query.
            $query = new $queryClass($this, $database);

        } catch (Throwable $thr) {
            throw new ModelQueryCreationFailedException($this, 'UPDATE', $thr);
        }

        return $query;
    }

    /**
     * Returns Insert Query object for this model.
     *
     * @param string $database Opt., null. To which database of this model you want a query?
     *
     * @since  v1.0
     * @return InsertQuery
     */
    public function newInsert(?string $database = null) : Engine
    {

        // Lvd.
        $query = null;

        try {

            // Lvd.
            $database   = $this->getDatabase($database);
            $engine     = PRZESLIJMI_SHORTQUERY_ENGINES[$this->getEngine($database)];
            $queryClass = $engine['createQuery'];

            // Create query.
            $query = new $queryClass($this, $database);

        } catch (Throwable $thr) {
            throw new ModelQueryCreationFailedException($this, 'INSERT', $thr);
        }

        return $query;
    }

    /**
     * Returns Delete Query object for this model.
     *
     * @param string $database Opt., null. To which database of this model you want a query?
     *
     * @since  v1.0
     * @return InsertQuery
     */
    public function newDelete(?string $database = null) : Engine
    {

        // Lvd.
        $query = null;

        try {

            // Lvd.
            $database   = $this->getDatabase($database);
            $engine     = PRZESLIJMI_SHORTQUERY_ENGINES[$this->getEngine($database)];
            $queryClass = $engine['deleteQuery'];

            // Create query.
            $query = new $queryClass($this, $database);

        } catch (Throwable $thr) {
            throw new ModelQueryCreationFailedException($this, 'DELETE', $thr);
        }

        return $query;
    }

    /**
     * Setter for namespace.
     *
     * @param string $namespace Namespace name.
     *
     * @since  v1.0
     * @throws ModelNamespaceWrosynException When Model namespace is wrong.
     * @return self
     */
    public function setNamespace(string $namespace) : self
    {

        // Add endslash to ease test.
        $namespace = trim($namespace, '\\') . '\\';

        // If namespace is proper.
        try {
            RegEx::ifMatches($namespace, '/^(([A-Z])+([a-zA-Z0-9_])*(\\\\){1}){3,}$/');
        } catch (Throwable $thr) {
            throw new ModelNamespaceWrosynException($namespace, $this, $thr);
        }

        // Add last slash.
        $namespace = trim($namespace, '\\');

        // Save.
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Getter for namespace.
     *
     * @param null|integer $sliceFrom   Optional. If only part of namespace if needed - slice from (starting from 0).
     * @param null|integer $sliceLength Optional. If only part of namespace if needed - slice length.
     *
     * @since  v1.0
     * @throws ModelNamespaceDonoexException When namespace is empty (null).
     * @return string
     */
    public function getNamespace(?int $sliceFrom = null, ?int $sliceLength = null) : string
    {

        // Throw on null.
        if (is_null($this->namespace) === true) {
            throw new ModelNamespaceDonoexException($this);
        }

        // If only slice of namespace is needed.
        if (is_null($sliceFrom) === false) {

            // Lvd.
            $array = explode('\\', $this->namespace);

            return implode('\\', array_slice($array, (int) $sliceFrom, $sliceLength));
        }

        return $this->namespace;
    }

    /**
     * Setter for instance class name.
     *
     * @param string $instanceClassName Instance class name.
     *
     * @since  v1.0
     * @throws ModelInstanceClassNameWrosynException When Model instance class name is wrong.
     * @return self
     */
    public function setInstanceClassName(string $instanceClassName) : self
    {

        // If namespace is proper.
        try {
            RegEx::ifMatches($instanceClassName, '/^([A-Z])+([a-zA-Z0-9_])*$/');
        } catch (Throwable $thr) {
            throw new ModelInstanceClassNameWrosynException($instanceClassName, $this, $thr);
        }

        // Save.
        $this->instanceClassName = $instanceClassName;

        return $this;
    }

    /**
     * Getter for instance class name.
     *
     * @since  v1.0
     * @throws ModelInstanceClassNameDonoexException When namespace is empty (null)..
     * @return string
     */
    public function getInstanceClassName() : string
    {

        // Throw on null.
        if (is_null($this->instanceClassName) === true) {
            throw new ModelInstanceClassNameDonoexException($this);
        }

        return $this->instanceClassName;
    }

    /**
     * Setter for collection class name..
     *
     * @param string $collectionClassName Instance class name.
     *
     * @since  v1.0
     * @throws ModelCollectionClassNameWrosynException When Model collection class name is wrong.
     * @return self
     */
    public function setCollectionClassName(string $collectionClassName) : self
    {

        // If namespace is proper.
        try {
            RegEx::ifMatches($collectionClassName, '/^([A-Z])+([a-zA-Z0-9_])*$/');
        } catch (Throwable $thr) {
            throw new ModelCollectionClassNameWrosynException($collectionClassName, $this, $thr);
        }

        // Save.
        $this->collectionClassName = $collectionClassName;

        return $this;
    }

    /**
     * Getter for collection class name.
     *
     * @since  v1.0
     * @throws ModelCollectionClassNameDonoexException When namespace is empty (null)..
     * @return string
     */
    public function getCollectionClassName() : string
    {

        // Throw on null.
        if (is_null($this->collectionClassName) === true) {
            throw new ModelCollectionClassNameDonoexException($this);
        }

        return $this->collectionClassName;
    }

    /**
     * Multigetter method - returns possible classes names and namespaces.
     *
     * Possible questions:
     * - namespace
     * - namespaceCore
     * - modelClass
     * - modelClassName
     * - instanceClass
     * - instanceClassName
     * - instanceCoreClassName
     * - collectionClass
     * - collectionClassName
     * - collectionCoreClassName
     * - parentClassName
     *
     * @param string $which One question as mentioned above.
     *
     * @since  v1.0
     * @return string
     *
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity
     */
    public function getClass(string $which) : string
    {

        // Lvd.
        $result = '';
        $set    = [
            'namespace', 'namespaceCore', 'modelClass', 'modelClassName', 'instanceClass',
            'instanceClassName', 'instanceCoreClassName', 'collectionClass', 'collectionClassName',
            'collectionCoreClassName', 'parentClassName'
        ];

        // Make work.
        if ($which === 'namespace') {
            $result = $this->getNamespace();

        } elseif ($which === 'namespaceCore') {
            $result = $this->getNamespace() . '\Core';

        } elseif ($which === 'modelClass') {
            $result = $this->getNamespace() . '\Core\\' . $this->getInstanceClassName() . 'Model';

        } elseif ($which === 'modelClassName') {
            $result = $this->getInstanceClassName() . 'Model';

        } elseif ($which === 'instanceClass') {
            $result = $this->getNamespace() . '\\' . $this->getInstanceClassName();

        } elseif ($which === 'instanceClassName') {
            $result = $this->getInstanceClassName();

        } elseif ($which === 'instanceCoreClassName') {
            $result = $this->getInstanceClassName() . 'Core';

        } elseif ($which === 'collectionClass') {
            $result = $this->getNamespace() . '\\' . $this->getCollectionClassName();

        } elseif ($which === 'collectionClassName') {
            $result = $this->getCollectionClassName();

        } elseif ($which === 'collectionCoreClassName') {
            $result = $this->getCollectionClassName() . 'Core';

        } elseif ($which === 'parentClassName') {
            $result = $this->getNamespace(2);

        }//end if

        if (empty($result) === false) {
            return $result;
        }

        throw (new ParamOtosetException('getClass:0', $set, $which))
            ->addHint('You\'re calling `getClass` method with wrong first parameter.');
    }

    /**
     * Adds Field for model.
     *
     * @param Field $field Field object.
     *
     * @since  v1.0
     * @throws ModelFieldNameAlrexException When trying to add new Field but it's name is already taken.
     * @return self
     */
    public function addField(Field $field) : self
    {

        // Check for duplicates.
        if (isset($this->fields[$field->getName()]) === true) {
            throw new ModelFieldNameAlrexException($field->getName(), $this);
        }

        // Add Field.
        $this->fields[$field->getName()] = $field;

        // Inform Field about Model in which it resides.
        $field->setModel($this);

        return $this;
    }

    /**
     * Return all Fields in Model.
     *
     * @since  v1.0
     * @return array Field[].
     */
    public function getFields() : array
    {

        return $this->fields;
    }

    /**
     * Return Field by given name.
     *
     * @param string $name Name of Field.
     *
     * @since  v1.0
     * @throws ModelFieldDonoexException When Field does not exists.
     * @return Field.
     */
    public function getFieldByName(string $name) : Field
    {

        // Check if Field exists.
        if (isset($this->fields[$name]) === false) {
            throw new ModelFieldDonoexException($name, $this);
        }

        return $this->fields[$name];
    }

    /**
     * Returns Field that is a primary key Field for this model.
     *
     * @todo Make better test for duplicating primary key or accept that there can be more then one.
     *
     * @since  v1.0
     * @throws ModelPrimaryKeyFieldAlrexException  When there are more than one Primary Keys in this Model.
     * @throws ModelPrimaryKeyFieldDonoexException When there is no Primary Key in this Model.
     * @return Field
     */
    public function getPrimaryKeyField() : Field
    {

        // Lvd.
        $result = null;

        // Try to find primary key.
        foreach ($this->getFields() as $field) {
            if ($field->isPrimaryKey() === true) {
                if (is_null($result) === true) {
                    $result = $field;
                } else {
                    throw new ModelPrimaryKeyFieldAlrexException($this);
                }
            }
        }

        // Found Field - return.
        if (is_null($result) === false) {
            return $result;
        }

        throw new ModelPrimaryKeyFieldDonoexException($this);
    }

    /**
     * Alias for `getPrimaryKeyField`.
     *
     * @since  v1.0
     * @return Field
     */
    public function getPkField() : Field
    {

        return $this->getPrimaryKeyField();
    }

    /**
     * Return list of Fields names.
     *
     * @since  v1.0
     * @return string[]
     */
    public function getFieldsNames() : array
    {

        return array_keys($this->getFields());
    }

    /**
     * Return list of getter methods for each Field.
     *
     * @since  v1.0
     * @return string[]
     */
    public function getFieldsGettersNames() : array
    {

        // Lvd.
        $result = [];

        // For each Field.
        foreach ($this->getFieldsNames() as $fieldName) {

            // Lvd.
            $fieldNameExploded = explode('_', $fieldName);

            // Convert every word.
            array_walk(
                $fieldNameExploded,
                function (&$value) {
                    $value = ucfirst($value);
                }
            );

            // Save part.
            $result[] = 'get' . implode('', $fieldNameExploded);
        }

        return $result;
    }

    /**
     * Return list of setter methods for each Field.
     *
     * @since  v1.0
     * @return string[]
     */
    public function getFieldsSettersNames() : array
    {

        // Lvd.
        $result = [];

        // For each Field.
        foreach ($this->getFieldsNames() as $fieldName) {

            // Lvd.
            $fieldNameExploded = explode('_', $fieldName);

            // Convert every word.
            array_walk(
                $fieldNameExploded,
                function (&$value) {
                    $value = ucfirst($value);
                }
            );

            // Save part.
            $result[] = 'set' . implode('', $fieldNameExploded);
        }

        return $result;
    }

    /**
     * Adds relation for model.
     *
     * @param Relation $relation Relation object.
     *
     * @since  v1.0
     * @throws ModelRelationNameAlrexException When trying to add next relation with the same name.
     * @return self
     */
    public function addRelation(Relation $relation) : self
    {

        // Check and throw.
        if (isset($this->relations[$relation->getName()]) === true) {
            throw new ModelRelationNameAlrexException($relation->getName(), $this);
        }

        // Add Field.
        $this->relations[$relation->getName()] = $relation;

        // Inform Field about Model in which it resides.
        $relation->setModelFrom($this->getClass('modelClass'));

        return $this;
    }

    /**
     * Return list of relations in model.
     *
     * @since  v1.0
     * @return array Relation[].
     */
    public function getRelations() : array
    {

        return $this->relations;
    }

    /**
     * Return Relation by given name.
     *
     * @param string $name Name of relation.
     *
     * @since  v1.0
     * @throws ModelRelationDonoexException If relation does not exists.
     * @return Relation.
     */
    public function getRelationByName(string $name) : Relation
    {

        // Check if Relation exists.
        if (isset($this->relations[$name]) === false) {
            throw new ModelRelationDonoexException($name, $this);
        }

        return $this->relations[$name];
    }

    /**
     * Return list of Fields names.
     *
     * @since  v1.0
     * @return array string[].
     */
    public function getRelationsNames() : array
    {

        return array_keys($this->relations);
    }

    /**
     * Creates and return new instance of this model.
     *
     * @since  v1.0
     * @throws ModelInstanceCreationFailedException When failed to create instance.
     * @return object
     */
    public function getNewInstance() : object
    {

        // Lvd.
        $instance = null;
        $name     = $this->namespace . '\\' . $this->instanceClassName;

        // Create.
        $instance = new $name();

        return $instance;
    }
}
