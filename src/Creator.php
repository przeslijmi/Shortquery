<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use Exception;
use Przeslijmi\CliApp\CliApp;
use Przeslijmi\CliApp\Report;
use Przeslijmi\Sexceptions\Exceptions\FileDonoexException;
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
    private $configs;

    /**
     * Main screen of application - creates files.
     *
     * @since  v1.0
     * @return void
     */
    public function create() : void
    {

        // Report.
        Report::info("\n" . '{[( Shortquery Creator )]} starts working, hello!' . "\n");

        // Check - incomplete params.
        $this->validateParams();

        // Check and readin configs.
        $this->readInModelsToCreate();

        /*
         * Now both are present:
         * $this->configs['settings'] - with src for vendor/app namespace's
         * $this->configs['models']   - with array of models to create
         */

        // Call to create every Model itself - for now.
        foreach ($this->configs['models'] as $model) {

            // Lvd.
            $vendorApp = $model->getNamespace(0, 2);

            // Create settings.
            $settings = [
                'srcDir' => $this->configs['settings']['src'][$vendorApp],
            ];

            // Model Model.
            $phpFile = new ModelPhpFile($settings, $model);
            $phpFile->prepare();
            $phpFile->save();
        }

        // Call to create all other files.
        foreach ($this->configs['models'] as $model) {

            // Lvd.
            $vendorApp = $model->getNamespace(0, 2);

            // Create settings.
            $settings = [
                'srcDir' => $this->configs['settings']['src'][$vendorApp],
            ];

            // Model CollCore.
            $phpFile = new CollectionCore($settings, $model);
            $phpFile->prepare();
            $phpFile->save();

            // Model Core.
            $phpFile = new InstanceCore($settings, $model);
            $phpFile->prepare();
            $phpFile->save();

            // Model Coll.
            $phpFile = new Collection($settings, $model);
            $phpFile->prepare();
            $phpFile->saveIfNotExists();

            // Model.
            $phpFile = new Instance($settings, $model);
            $phpFile->prepare();
            $phpFile->saveIfNotExists();
        }//end foreach

        // Report.
        Report::info("\n" . '{[( Shortquery Creator )]} ends, bye bye!' . "\n");
    }

    /**
     * Check and read in configs.
     *
     * @since  v1.0
     * @throws FileDonoexException
     * @throws ConfigurationCorruptedException
     * @throws ConfigurationIncompleteException
     * @return void
     */
    private function readInModelsToCreate()
    {

        // Lvd.
        $configUri = $this->getParams()->getParam('config');

        // Check if source dir exists.
        if (file_exists($configUri) === false) {
            $hint = 'Creator has to take configs from a file given as param -c (--config). File is missing.';
            throw (new FileDonoexException('shortqueryModelsCreatorConfigUri', $configUri))
                ->addInfo('hint', $hint);
        }

        // Get configs.
        $this->configs = include $configUri;

        // Check if ARRAY was read.
        if (is_array($this->configs) === false) {
            throw new ConfigurationCorruptedException();
        }
    }

    /**
     * Check are all neded params present.
     *
     * @since  v1.0
     * @return void
     */
    private function validateParams() : void
    {

        // Check params if exist.
        $configUri = $this->getParams()->getParam('config');
    }
}
