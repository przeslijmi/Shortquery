<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Creator;

use Przeslijmi\Sexceptions\Exceptions\FileDonoexException;
use Przeslijmi\Sexceptions\Exceptions\ParamOtosetException;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\SiHDD\File;
use Przeslijmi\Sivalidator\RegEx;
use stdClass;

/**
 * Parent for classes that create ShortQuery models *.php files.
 */
abstract class PhpFile
{

    /**
     * Definitions of template's names.
     */
    const TPL_COLLECTION      = 'Collection';
    const TPL_COLLECTION_CORE = 'CollectionCore';
    const TPL_INSTANCE        = 'Instance';
    const TPL_INSTANCE_CORE   = 'InstanceCore';
    const TPL_MODEL           = 'Model';

    /**
     * Model for which file have to be created.
     *
     * @var Model
     */
    protected $model;

    /**
     * Settings from Creator.
     *
     * @var array
     */
    protected $settings;

    /**
     * Contents of the final PHP file.
     *
     * @var string
     */
    protected $contents;

    /**
     * List of namespaces used by file.
     *
     * @var string[]
     */
    protected $namespaces;

    /**
     * Constructor.
     *
     * @param array $settings Settings to use to create.
     * @param Model $model    Model for which to create.
     *
     * @since v1.0
     */
    public function __construct(array $settings, Model $model)
    {

        // Save.
        $this->model    = $model;
        $this->settings = $settings;
    }

    /**
     * Saves file to final location.
     *
     * @param boolean $overwrite If set to true and file exists - it will be overwritten.
     *
     * @since  v1.0
     * @return self
     */
    public function save(bool $overwrite) : self
    {

        // Get path.
        $path = $this->getUri();
        $path = str_replace('\\', '/', $path);

        // Ignore saving when file exists (if needed).
        if (file_exists($path) === true && $overwrite === false) {
            return $this;
        }

        // Save file.
        $file = new File($path);
        $file->setContents($this->contents);
        $file->save();

        return $this;
    }

    /**
     * Return TPL file URI to use.
     *
     * @param string $name Template name (eg. `Collection`) - one from `TPL_*` constants.
     *
     * @since  v1.0
     * @return string
     */
    public function getTplUri(string $name) : string
    {

        // Lvd.
        $properRange = [
            self::TPL_COLLECTION,
            self::TPL_COLLECTION_CORE,
            self::TPL_INSTANCE,
            self::TPL_INSTANCE_CORE,
            self::TPL_MODEL
        ];

        // Check template name.
        if (in_array($name, $properRange) === false) {
            throw new ParamOtosetException('templateName', $properRange, $name);
        }

        // Define possible URIs.
        $possibleUris = [
            'vendor/przeslijmi/shortquery/tpl/' . $name . '.local.tpl',
            'vendor/przeslijmi/shortquery/tpl/' . $name . '.tpl',
        ];

        // Check every possible URI.
        foreach ($possibleUris as $uri) {
            if (file_exists($uri) === true) {
                return $uri;
            }
        }

        // Throw on failure.
        $hint  = 'None of possible locations for shortQuery Creator template file worked from location `';
        $hint .= getcwd() . '`.';
        throw (new FileDonoexException('templateFile', implode(', ', $possibleUris)))->addHint($hint);
    }

    /**
     * Getter for contents.
     *
     * @since  v1.0
     * @return string
     */
    public function getContents() : string
    {

        return $this->contents;
    }

    /**
     * Returns first line (start tag and declaration) for every PHP file.
     *
     * @return string
     */
    protected function getFirstLinePhp() : string
    {

        return chr(60) . chr(63) . 'php declare(strict_types=1);' . "\n";
    }

    protected function addUse(string $namespace) : self
    {

        $this->namespaces[$namespace] = $namespace;

        return $this;
    }

    protected function showNamespaces() : void
    {

        // Lvd.
        $result = '';

        // Unique and sort.
        sort($this->namespaces);

        // Fill up.
        foreach ($this->namespaces as $namespace) {
            $result .= 'use ' . $namespace . ';' . "\n";
        }

        echo $result;
    }
}
