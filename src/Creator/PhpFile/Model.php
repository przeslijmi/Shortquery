<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Creator\PhpFile;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Creator\PhpFile;

/**
 * ShortQuery creator for Core\{instance}Model PHP File.
 */
class Model extends PhpFile
{

    /**
     * Prepare contents of the file.
     *
     * @return self
     */
    public function prepare() : self
    {

        // Call to create.
        ob_start();
        $success        = include $this->getTplUri(PhpFile::TPL_MODEL);
        $this->contents = ob_get_clean();

        // Add PHP beginning.
        $this->contents = $this->getFirstLinePhp() . $this->contents;
        $this->contents = trim($this->contents) . "\n";

        return $this;
    }

    /**
     * Return URI of the file to use.
     *
     * @return string
     */
    public function getUri() : string
    {

        // Lvd.
        $result  = $this->settings['srcDir'];
        $result .= $this->model->getClass('parentClassName');
        $result .= '\\Core\\';
        $result .= $this->model->getClass('modelClassName');
        $result .= '.php';

        return $result;
    }
}
