<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use Exception;
use Przeslijmi\CliApp\CliApp;
use Przeslijmi\CliApp\Report;
use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Sexceptions\Exceptions\FileDonoexException;
use Przeslijmi\Sexceptions\Exceptions\MethodFopException;
use Przeslijmi\Shortquery\Creator\Model;
use Przeslijmi\Shortquery\Creator\PhpFile\Collection;
use Przeslijmi\Shortquery\Creator\PhpFile\CollectionCore;
use Przeslijmi\Shortquery\Creator\PhpFile\Instance;
use Przeslijmi\Shortquery\Creator\PhpFile\InstanceCore;
use Przeslijmi\Shortquery\Creator\PhpFile\Model as ModelPhpFile;
use Przeslijmi\Shortquery\Exceptions\Creator\ConfigurationCorruptedException;
use Przeslijmi\Shortquery\Exceptions\Creator\ConfigurationIncompleteException;
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
     * Main screen of application - creates files.
     *
     * @throws MethodFopException When no models are given to be created.
     * @return void
     */
    public function create() : void
    {

        // Report.
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
            throw new MethodFopException('noModelsGiven');
        }

        // Define overwriting.
        $overwriteCore    = ( $this->getParams()->getParam('overwriteCore', false) ?? true );
        $overwriteNonCore = ( $this->getParams()->getParam('overwriteNonCore', false) ?? false );

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

            // Model Core.
            $phpFile = new InstanceCore($settings, $model);
            $phpFile->prepare();
            $phpFile->save($overwriteCore);

            // Model Coll.
            $phpFile = new Collection($settings, $model);
            $phpFile->prepare();
            $phpFile->save($overwriteNonCore);

            // Model.
            $phpFile = new Instance($settings, $model);
            $phpFile->prepare();
            $phpFile->save($overwriteNonCore);
        }//end foreach

        // Report.
        $this->log('notice', 'Przeslijmi\Shortquery\Creator: bye bye!');
    }

    /**
     * Check and read in schema.
     *
     * @throws FileDonoexException             When file given as creator schema does not exists.
     * @throws ConfigurationCorruptedException When configuration file is corrupted.
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
                $hint = 'Creator has to take schema from a file given as param -su (--schemaUri). File is missing.';
                throw (new FileDonoexException('shortqueryModelsCreatorSchemaUri', $schemaUri))
                    ->addInfo('hint', $hint);
            }

            // Get schema.
            $this->schema = include $schemaUri;

            // Add schema settings uri.
            $this->schema['settings']['schemaSettingsUri'] = realpath(dirname($schemaUri));
        }//end if

        // Check if ARRAY was read.
        if (is_array($this->schema) === false) {
            throw new ConfigurationCorruptedException();
        }

        return $this;
    }

    /**
     * Check are all neded params present.
     *
     * @throws ClassFopException When no Schema has been given.
     * @return void
     */
    private function validateParams() : void
    {

        // Lvd.
        $isSchemaUri = $this->getParams()->isParamSet('schemaUri');
        $isSchema    = $this->getParams()->isParamSet('schema');

        // Check params if exist.
        if ($isSchemaUri === false && $isSchema === false) {
            $hint  = 'Creator has to take schema from a file given as param -su (--schemaUri)';
            $hint .= 'or as direct injection `->setParam(\'schema\')`. None happend.';
            throw (new ClassFopException('shortqueryModelsCreatorSchemaMissing'))
                ->addInfo('hint', $hint);
        }
    }
}
