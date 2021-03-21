<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

use Przeslijmi\Shortquery\Data\Collection;
use Przeslijmi\Shortquery\Data\Field;
use Przeslijmi\Shortquery\Data\Relation;
use Przeslijmi\Shortquery\Engine;
use Przeslijmi\Shortquery\Exceptions\Data\CollectionUnknownNameGetterException;
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
use Przeslijmi\Shortquery\Exceptions\Model\ModelPrimaryKeyFieldAlrexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelPrimaryKeyFieldDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelQueryCreationFailedException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelRelationDonoexException;
use Przeslijmi\Shortquery\Exceptions\Model\ModelRelationNameAlrexException;
use Przeslijmi\Sivalidator\RegEx;
use Throwable;

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
     * @throws ModelNameWrosynException When Model name is wrong.
     * @return self
     */
    public function setName(string $name) : self
    {

        // If name is proper.
        try {
            RegEx::ifMatches($name, '/^([a-zA-Z_])+([a-zA-Z0-9_])*$/');
        } catch (Throwable $thr) {
            throw new ModelNameWrosynException([ get_class($this), $name ], 0, $thr);
        }

        // Save.
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for name.
     *
     * @throws ModelNameDonoexException When name is empty (null).
     * @return string
     */
    public function getName() : string
    {

        // Throw on null.
        if (is_null($this->name) === true) {
            throw new ModelNameDonoexException([ get_class($this) ]);
        }

        return $this->name;
    }

    /**
     * Setter for database name.
     *
     * @param string $database Engine name.
     *
     * @throws ModelDatabaseNameOtosetException When Model database name is wrong.
     * @return self
     */
    public function setDatabases(string $database) : self
    {

        // If any database is not proper.
        foreach (func_get_args() as $database) {
            if (isset(PRZESLIJMI_SHORTQUERY_DATABASES[$database]) === false) {
                throw new ModelDatabaseNameOtosetException([
                    implode(', ', array_keys(PRZESLIJMI_SHORTQUERY_DATABASES)),
                    $database,
                    $this->getName(),
                    get_class($this),
                ]);
            }
        }

        // Save.
        $this->databases = func_get_args();

        return $this;
    }

    /**
     * Getter for databases.
     *
     * @return string[]
     */
    public function getDatabases() : array
    {

        return $this->databases;
    }

    /**
     * Getter for databases as string (format: `'database1', 'database2'`).
     *
     * @return string
     */
    public function getDatabasesAsString() : string
    {

        return '\'' . implode('\', \'', $this->databases) . '\'';
    }

    /**
     * Getter for databases.
     *
     * @param string $database Opt., null. To which database of this model you want a query.
     *
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

        throw new ModelDatabaseDonoexException([ get_class($this) ]);
    }

    /**
     * Getter for engine name.
     *
     * @param string $database Opt., null. To which database of this model you want a query.
     *
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
            throw new ModelEngineDonoexException([ get_class($this) ], 0, $thr);
        }

        return $engine;
    }

    /**
     * Returns Select Query object for this model.
     *
     * @param string $database Opt., null. To which database of this model you want a query.
     *
     * @throws ModelQueryCreationFailedException When creation of query failed.
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
            throw new ModelQueryCreationFailedException(
                [ $this->getName(), get_class($this), 'SELECT' ],
                0,
                $thr
            );
        }

        return $query;
    }

    /**
     * Returns Update Query object for this model.
     *
     * @param string $database Opt., null. To which database of this model you want a query.
     *
     * @throws ModelQueryCreationFailedException When creation of query failed.
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
            throw new ModelQueryCreationFailedException(
                [ $this->getName(), get_class($this), 'UPDATE' ],
                0,
                $thr
            );
        }

        return $query;
    }

    /**
     * Returns Insert Query object for this model.
     *
     * @param string $database Opt., null. To which database of this model you want a query.
     *
     * @throws ModelQueryCreationFailedException When creation of query failed.
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
            throw new ModelQueryCreationFailedException(
                [ $this->getName(), get_class($this), 'INSERT' ],
                0,
                $thr
            );
        }

        return $query;
    }

    /**
     * Returns Delete Query object for this model.
     *
     * @param string $database Opt., null. To which database of this model you want a query.
     *
     * @throws ModelQueryCreationFailedException When creation of query failed.
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
            throw new ModelQueryCreationFailedException(
                [ $this->getName(), get_class($this), 'DELETE' ],
                0,
                $thr
            );
        }

        return $query;
    }

    /**
     * Setter for namespace.
     *
     * @param string $namespace Namespace name.
     *
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
            throw new ModelNamespaceWrosynException([ get_class($this), $namespace ], 0, $thr);
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
     * @throws ModelNamespaceDonoexException When namespace is empty (null).
     * @return string
     */
    public function getNamespace(?int $sliceFrom = null, ?int $sliceLength = null) : string
    {

        // Throw on null.
        if (is_null($this->namespace) === true) {
            throw new ModelNamespaceDonoexException([ $this->getName(), get_class($this) ]);
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
     * @throws ModelInstanceClassNameWrosynException When Model instance class name is wrong.
     * @return self
     */
    public function setInstanceClassName(string $instanceClassName) : self
    {

        // If namespace is proper.
        try {
            RegEx::ifMatches($instanceClassName, '/^([A-Z])+([a-zA-Z0-9_])*$/');
        } catch (Throwable $thr) {
            throw new ModelInstanceClassNameWrosynException([ get_class($this), $instanceClassName ], 0, $thr);
        }

        // Save.
        $this->instanceClassName = $instanceClassName;

        return $this;
    }

    /**
     * Getter for instance class name.
     *
     * @throws ModelInstanceClassNameDonoexException When namespace is empty (null)..
     * @return string
     */
    public function getInstanceClassName() : string
    {

        // Throw on null.
        if (is_null($this->instanceClassName) === true) {
            throw new ModelInstanceClassNameDonoexException([ $this->getName(), get_class($this) ]);
        }

        return $this->instanceClassName;
    }

    /**
     * Setter for collection class name..
     *
     * @param string $collectionClassName Instance class name.
     *
     * @throws ModelCollectionClassNameWrosynException When Model collection class name is wrong.
     * @return self
     */
    public function setCollectionClassName(string $collectionClassName) : self
    {

        // If namespace is proper.
        try {
            RegEx::ifMatches($collectionClassName, '/^([A-Z])+([a-zA-Z0-9_])*$/');
        } catch (Throwable $thr) {
            throw new ModelCollectionClassNameWrosynException([ get_class($this), $collectionClassName ], 0, $thr);
        }

        // Save.
        $this->collectionClassName = $collectionClassName;

        return $this;
    }

    /**
     * Getter for collection class name.
     *
     * @throws ModelCollectionClassNameDonoexException When namespace is empty (null).
     * @return string
     */
    public function getCollectionClassName() : string
    {

        // Throw on null.
        if (is_null($this->collectionClassName) === true) {
            throw new ModelCollectionClassNameDonoexException([ $this->getName(), get_class($this) ]);
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
     * @throws CollectionUnknownNameGetterException When calling method with wrong parameter.
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

        // Throw.
        throw new CollectionUnknownNameGetterException([ implode(', ', $set), $which ]);
    }

    /**
     * Adds Field for model.
     *
     * @param Field $field Field object.
     *
     * @throws ModelFieldNameAlrexException When trying to add new Field but it's name is already taken.
     * @return self
     */
    public function addField(Field $field) : self
    {

        // Check for duplicates.
        if (isset($this->fields[$field->getName()]) === true) {
            throw new ModelFieldNameAlrexException([
                $this->getName(), get_class($this), $field->getName()
            ]);
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
     * @throws ModelFieldDonoexException When Field does not exists.
     * @return Field.
     */
    public function getFieldByName(string $name) : Field
    {

        // Check if Field exists.
        if (isset($this->fields[$name]) === false) {
            throw new ModelFieldDonoexException([ $this->getName(), get_class($this), $name ]);
        }

        return $this->fields[$name];
    }

    /**
     * Return Field by given name if that field exists (otherwise return `null`).
     *
     * @param string $name Name of Field.
     *
     * @return null|Field.
     */
    public function getFieldByNameIfExists(string $name) : ?Field
    {

        // Check if Field exists - if not - return null.
        if (isset($this->fields[$name]) === false) {
            return null;
        }

        return $this->fields[$name];
    }

    /**
     * Returns Field that is a primary key Field for this model.
     *
     * @todo Make better test for duplicating primary key or accept that there can be more then one.
     *
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
                    throw new ModelPrimaryKeyFieldAlrexException([ $this->getName(), get_class($this) ]);
                }
            }
        }

        // Found Field - return.
        if (is_null($result) === false) {
            return $result;
        }

        throw new ModelPrimaryKeyFieldDonoexException([ $this->getName(), get_class($this) ]);
    }

    /**
     * Alias for `getPrimaryKeyField`.
     *
     * @return Field
     */
    public function getPkField() : Field
    {

        return $this->getPrimaryKeyField();
    }

    /**
     * Return list of Fields names.
     *
     * @return string[]
     */
    public function getFieldsNames() : array
    {

        return array_keys($this->getFields());
    }

    /**
     * Return list of getter methods for each Field.
     *
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
     * @throws ModelRelationNameAlrexException When trying to add next relation with the same name.
     * @return self
     */
    public function addRelation(Relation $relation) : self
    {

        // Check and throw.
        if (isset($this->relations[$relation->getName()]) === true) {
            throw new ModelRelationNameAlrexException([
                $this->getName(),
                get_class($this),
                $relation->getName(),
            ]);
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
     * @throws ModelRelationDonoexException If relation does not exists.
     * @return Relation.
     */
    public function getRelationByName(string $name) : Relation
    {

        // Check if Relation exists.
        if (isset($this->relations[$name]) === false) {
            throw new ModelRelationDonoexException([
                $this->getName(),
                get_class($this),
                $name,
            ]);
        }

        return $this->relations[$name];
    }

    /**
     * Return list of Fields names.
     *
     * @return array string[].
     */
    public function getRelationsNames() : array
    {

        return array_keys($this->relations);
    }

    /**
     * Creates and return new instance of this model.
     *
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
