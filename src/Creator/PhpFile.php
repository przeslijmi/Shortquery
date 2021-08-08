<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Creator;

use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Exceptions\Creator\TemplateFileDonoexException;
use Przeslijmi\Shortquery\Exceptions\Creator\TemplateNameOtosetException;
use Przeslijmi\SiHDD\File;

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
    protected $namespaces = [];

    /**
     * Constructor.
     *
     * @param array $settings Settings to use to create.
     * @param Model $model    Model for which to create.
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
     * @return self
     */
    public function save(bool $overwrite) : self
    {

        // Get path.
        $path = $this->getUri();

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
     * @throws TemplateNameOtosetException When Template of this name does not exists.
     * @throws TemplateFileDonoexException When template file does not exists.
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
            throw new TemplateNameOtosetException([
                implode(', ', $properRange),
                $name
            ]);
        }

        // Define possible URIs.
        $possibleUris = [
            'tpl/' . $name . '.local.tpl',
            'tpl/' . $name . '.tpl',
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
        throw new TemplateFileDonoexException([ getcwd(), implode(', ', $possibleUris) ]);
    }

    /**
     * Getter for contents.
     *
     * @return string
     */
    public function getContents() : string
    {

        return $this->contents;
    }

    public function getShortUri() : string
    {

        // Lvd.
        $fullUri = $this->getUri();
        $baseUri = rtrim(str_replace('\\', '/', $this->settings['schemaSettingsUri']), '/') . '/';

        return str_replace('\\', '/', substr($fullUri, strlen($baseUri)));
    }

    /**
     * Returns first line (start tag and declaration) for every PHP file.
     *
     * @return string
     */
    protected function getFirstLinePhp() : string
    {

        return chr(60) . chr(63) . 'php' . "\n\n" . 'declare(strict_types=1);' . "\n";
    }

    /**
     * Add next `use` - ie. create class alias if neccesary.
     *
     * @param string $namespace Namespace to use.
     *
     * @return self
     */
    protected function addUse(string $namespace) : self
    {

        // Find class name for this namespace.
        $className = substr($namespace, ( strrpos($namespace, '\\') + 1 ));
        $aliasName = $className;

        // Find if this class name is not taken and if so - change alias.
        foreach ($this->namespaces as $namespaceInfo) {
            if ($namespaceInfo['className'] === $className) {
                $aliasName = $className . rand(1000, 9999);
            }
        }

        // Save namespace.
        $this->namespaces[$namespace] = [
            'namespace' => $namespace,
            'className' => $className,
            'aliasName' => $aliasName,
        ];

        return $this;
    }

    /**
     * Echo `use` section of class.
     *
     * @return void
     */
    protected function showNamespaces() : void
    {

        // Lvd.
        $result = '';

        // Unique and sort.
        ksort($this->namespaces);

        // Fill up.
        foreach ($this->namespaces as $namespace) {

            // Lvd.
            $addAlias = '';

            if ($namespace['aliasName'] !== $namespace['className']) {
                $addAlias = ' as ' . $namespace['aliasName'];
            }

            $result .= 'use ' . $namespace['namespace'] . $addAlias . ';' . "\n";
        }

        echo $result;
    }

    /**
     * Returns alias class name for given full class (includeing changes made in `use` section).
     *
     * @param string $namespace Namespace of class to return alias class name.
     *
     * @return string
     */
    protected function getClassName(string $namespace) : string
    {

        return ( $this->namespaces[$namespace]['aliasName'] ?? 'UnknownClass' );
    }
}
