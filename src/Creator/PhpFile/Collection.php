<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Creator\PhpFile;

use Przeslijmi\Sexceptions\Exceptions\ClassFopException;
use Przeslijmi\Shortquery\Creator\PhpFile;

/**
 * ShortQuery creator for {collection} Model PHP File.
 */
class Collection extends PhpFile
{

    /**
     * Prepare contents of the file.
     *
     * @since  v1.0
     * @return self
     */
    public function prepare() : self
    {

        // Call to create.
        ob_start();
        $success        = include $this->getTplUri(PhpFile::TPL_COLLECTION);
        $this->contents = ob_get_clean();

        // Add PHP beginning.
        $this->contents = $this->getFirstLinePhp() . $this->contents;
        $this->contents = trim($this->contents) . PHP_EOL;

        // Throw.
        if ($success === false) {
            throw new ClassFopException('includingTemplateForShortQueryCreator');
        }

        return $this;
    }

    /**
     * Return URI of the file to use.
     *
     * @since  v1.0
     * @return string
     */
    public function getUri() : string
    {

        // Lvd.
        $result  = $this->settings['srcDir'];
        $result .= $this->model->getClass('parentClassName');
        $result .= '/';
        $result .= $this->model->getClass('collectionClassName');
        $result .= '.php';

        return $result;
    }
}
