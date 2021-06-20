<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use Przeslijmi\CliApp\CliApp;
use Przeslijmi\Shortquery\Creator\Model;
use Przeslijmi\Shortquery\Creator\PhpFile\Collection;
use Przeslijmi\Shortquery\Creator\PhpFile\CollectionCore;
use Przeslijmi\Shortquery\Creator\PhpFile\Instance;
use Przeslijmi\Shortquery\Creator\PhpFile\InstanceCore;
use Przeslijmi\Shortquery\Creator\PhpFile\Model as ModelPhpFile;
use Przeslijmi\Shortquery\Exceptions\Creator\ModelsInSchemaDonoexException;
use Przeslijmi\Shortquery\Exceptions\Creator\SchemaFileCorruptedException;
use Przeslijmi\Shortquery\Exceptions\Creator\SchemaFileDonoexException;
use Przeslijmi\Shortquery\Exceptions\Creator\SchemaMissingException;
use stdClass;

/**
 * CLI tool used to recreate PHP files for every Model.
 */
class Creator extends CliApp
{

    /**
     * Configs included on bootstrap.
     *
     * @var array
     */
    private $schema;

    /**
     * Created uris (list).
     *
     * @var string[]
     */
    private $createdUris;

    /**
     * Main screen of application - creates files.
     *
     * @throws ModelsInSchemaDonoexException When no models are given to be created.
     * @return void
     */
    public function create() : void
    {

        // Log.
        $this->log('notice', 'Przeslijmi\Shortquery\Creator: hello!');

        // Check - incomplete params.
        $this->validateParams();

        // Check and readin schema.
        $this->readInModelsToCreate();

        /*
         * Now both are present:
         * $this->schema['settings'] - with src for vendor/app namespace's
         * $this->schema['models']   - with array of models to create
         */

        // Check if there are any models.
        if (isset($this->schema['models']) === false || count($this->schema['models']) === 0) {
            throw new ModelsInSchemaDonoexException();
        }

        // Define overwriting.
        $overwriteCore    = ( $this->getParams()->getParam('overwriteCore', false) ?? true );
        $overwriteNonCore = ( $this->getParams()->getParam('overwriteNonCore', false) ?? false );
        $deleteOtherPhps  = ( $this->getParams()->getParam('deleteOtherPhps', false) ?? 'none' );

        // Call to create every Model itself - for now.
        foreach ($this->schema['models'] as $model) {

            // Lvd.
            $vendorApp = $model->getNamespace(0, 2);

            // Create settings.
            $settings = [
                'schemaSettingsUri' => $this->schema['settings']['schemaSettingsUri'],
                'srcDir' => $this->schema['settings']['src'][$vendorApp],
            ];

            // Model Model.
            $phpFile = new ModelPhpFile($settings, $model);
            $phpFile->prepare();
            $phpFile->save($overwriteCore);
            $this->createdUris[] = $phpFile->getShortUri();
        }

        // Call to create all other files.
        foreach ($this->schema['models'] as $model) {

            // Lvd.
            $vendorApp = $model->getNamespace(0, 2);

            // Create settings.
            $settings = [
                'schemaSettingsUri' => $this->schema['settings']['schemaSettingsUri'],
                'srcDir' => $this->schema['settings']['src'][$vendorApp],
            ];

            // Model CollCore.
            $phpFile = new CollectionCore($settings, $model);
            $phpFile->prepare();
            $phpFile->save($overwriteCore);
            $this->createdUris[] = $phpFile->getShortUri();

            // Model Core.
            $phpFile = new InstanceCore($settings, $model);
            $phpFile->prepare();
            $phpFile->save($overwriteCore);
            $this->createdUris[] = $phpFile->getShortUri();

            // Model Coll.
            $phpFile = new Collection($settings, $model);
            $phpFile->prepare();
            $phpFile->save($overwriteNonCore);
            $this->createdUris[] = $phpFile->getShortUri();

            // Model.
            $phpFile = new Instance($settings, $model);
            $phpFile->prepare();
            $phpFile->save($overwriteNonCore);
            $this->createdUris[] = $phpFile->getShortUri();
        }//end foreach

        // Call to delete other files from src.
        $this->deleteOtherPhps($deleteOtherPhps);

        // Log.
        $this->log('notice', 'Przeslijmi\Shortquery\Creator: bye bye!');
    }

    /**
     * Check and read in schema.
     *
     * @throws SchemaFileDonoexException    When file given as creator schema does not exists.
     * @throws SchemaFileCorruptedException When configuration file is corrupted.
     * @return self
     */
    private function readInModelsToCreate() : self
    {

        // Decide.
        if ($this->getParams()->isParamSet('schema') === true) {

            // If no schemaUri param is given - it means there is no file to send.
            $this->schema = $this->getParams()->getParam('schema');

        } else {

            // Otherwise - it is schema from file.
            $schemaUri  = rtrim(str_replace('\\', '/', $this->getParams()->getParam('baseDir')), '/') . '/';
            $schemaUri .= str_replace('\\', '/', $this->getParams()->getParam('schemaUri'));

            // Check if source dir exists.
            if (file_exists($schemaUri) === false) {
                throw new SchemaFileDonoexException([ $schemaUri ]);
            }

            // Get schema.
            $this->schema = include $schemaUri;

            // Add schema settings uri.
            $this->schema['settings']['schemaSettingsUri'] = realpath(dirname($schemaUri));
        }//end if

        // Check if ARRAY was read.
        if (is_array($this->schema) === false) {
            throw new SchemaFileCorruptedException([ ( $schemaUri ?? 'not from uri!' ) ]);
        }

        return $this;
    }

    /**
     * Deletes any other PHP files other than created inside working directory.
     *
     * First param means:
     *   - `none` - do not delete any files,
     *   - `onlyShortquery` - only files that directly relate to Shortquery elements,
     *   - `any` - delete all PHP files in dir and subdirs.
     *
     * @param string $mode One of: `none`, `onlyShortquery` or `any`.
     *
     * @return void
     */
    private function deleteOtherPhps(string $mode) : void
    {

        // Short way.
        if ($mode === 'none') {
            return;
        }

        // Find existing files.
        foreach ($this->schema['settings']['src'] as $namespace => $src) {
            $existing = array_merge(
                ( $existing ?? [] ),
                $this->findAllPhpFilesInDirRecursively($src, true)
            );
        }

        // Create short list - ie. list of presumabely unneeded PHP files.
        $shortList = array_values(array_diff($existing, $this->createdUris));

        // Depending on mode - `any` will delete all files from the shortlist, while `onlyShortquery`
        // is safer thus smaller - will only delete those that have one of those inside:
        // `<shortquery-role:collection>`
        // `<shortquery-role:collection-core>`
        // `<shortquery-role:instance>`
        // `<shortquery-role:instance-core>`
        // `<shortquery-role:model>`
        foreach ($shortList as $phpUri) {

            // Lvd.
            $delete = false;

            // Decide on deletion.
            if ($mode === 'any') {
                $delete = true;
            } elseif ($mode === 'onlyShortquery') {
                $delete = ( (bool) strpos(file_get_contents($phpUri), '`<shortquery-role:'));
            }

            // Delete if decided to.
            if ($delete === true) {
                unlink($phpUri);
            }
        }
    }

    /**
     * Check are all neded params present.
     *
     * @throws SchemaMissingException When no Schema has been given.
     * @return void
     */
    private function validateParams() : void
    {

        // Lvd.
        $isSchemaUri = $this->getParams()->isParamSet('schemaUri');
        $isSchema    = $this->getParams()->isParamSet('schema');

        // Check params if exist.
        if ($isSchemaUri === false && $isSchema === false) {
            throw new SchemaMissingException();
        }
    }

    private function findAllPhpFilesInDirRecursively(string $dir, bool $firstIteration = false) : array
    {

        $found = [];
        $dir   = rtrim(str_replace('\\', '/', $dir), '/') . '/';

        if ($firstIteration === true) {
            $dir .= 'Models/';
        }

        foreach (glob($dir . '*') as $element) {
            if (is_dir($element) === true) {
                $found = array_merge($found, $this->findAllPhpFilesInDirRecursively($element));
            } elseif (substr($element, -4) === '.php') {
                $found[] = $element;
            }
        }

        return $found;
    }
}
