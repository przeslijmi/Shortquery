<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\ForTests;

use Przeslijmi\Shortquery\Creator;

/**
 * Bootstrap for Creator.
 *
 * Creator recreates PHP files for all models, instances and collections.
 */
class CreatorStarter
{

    /**
     * Does non-core files has to be overwritten. Default `false`.
     *
     * @var boolean
     */
    private $overwriteNonCore = false;

    /**
     * Does core files has to be overwritten. Default `true`.
     *
     * @var boolean
     */
    private $overwriteCore = true;

    /**
     * Starts starter.
     *
     * Example of schema file: `resources/schemaForTesting.php`.
     *
     * @param string $schemaFileName File with instructions.
     *
     * @return self
     */
    public function run(string $schemaFileName) : self
    {

        // Lvd.
        $schemaDir = rtrim(str_replace('\\', '/', __DIR__), '/');
        $schemaDir = substr($schemaDir, 0, strrpos($schemaDir, '/'));
        $schemaDir = substr($schemaDir, 0, strrpos($schemaDir, '/'));
        $schemaUri = $schemaDir . '/' . $schemaFileName;

        // Call creator.
        $creator = new Creator();
        $creator->getParams()->setOperation('create');
        $creator->getParams()->setParam('schemaUri', $schemaUri);
        $creator->getParams()->setParam('overwriteNonCore', $this->overwriteNonCore);
        $creator->getParams()->setParam('overwriteCore', $this->overwriteCore);
        $creator->start();

        return $this;
    }

    /**
     * Setter for does non-core files has to be overwritten.
     *
     * @param boolean $overwriteNonCore Does non-core files has to be overwritten.
     *
     * @return self
     */
    public function setOverwriteNonCore(bool $overwriteNonCore) : self
    {

        $this->overwriteNonCore = $overwriteNonCore;

        return $this;
    }

    /**
     * Setter for does core files has to be overwritten.
     *
     * @param boolean $overwriteCore Does core files has to be overwritten.
     *
     * @return self
     */
    public function setOverwriteCore(bool $overwriteCore) : self
    {

        $this->overwriteCore = $overwriteCore;

        return $this;
    }
}
