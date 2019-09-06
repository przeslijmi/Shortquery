<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Sexceptions\Exceptions\FileDonoexException;
use Przeslijmi\Sexceptions\Exceptions\ParamOtosetException;
use Przeslijmi\Shortquery\Creator\PhpFile;
use Przeslijmi\Shortquery\Creator\PhpFile\Model as ModelPhpFile;
use Przeslijmi\Shortquery\ForTests\CreatorStarter;
use Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel;
use Throwable;

/**
 * Methods for testing More methods of Creator.
 */
final class CreatorTest extends TestCase
{

    /**
     * Start model Creator.
     *
     * @return void
     */
    public function testModelCreator() : void
    {

        $md = new CreatorStarter();
        $md->run('schemaForTesting.php');

        $this->assertEquals(1, 1);
    }

    /**
     * Test if Starting Creator without overwriting works.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testModelCreatorWithoutOverwriting() : void
    {

        $md = new CreatorStarter();
        $md->setOverwriteCore(false);
        $md->setOverwriteNonCore(false);
        $md->run('schemaForTesting.php');

        $this->assertEquals(1, 1);
    }

    /**
     * Test if creating proper model File works.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testIfCreatingModelPhpFileWorks() : void
    {

        // Lvd.
        $model    = new GirlModel();
        $settings = [ 'srcDir' => 'test' ];

        // Create file.
        $file = new ModelPhpFile($settings, $model);
        $file->getTplUri(PhpFile::TPL_MODEL);
        $file->prepare();

        // Test.
        $this->assertEquals('string', gettype($file->getContents()));
    }

    /**
     * Test if sending nonexisting template will throw.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testIfSendingNonexistingTemplateThrows() : void
    {

        // Lvd.
        $model    = new GirlModel();
        $settings = [ 'srcDir' => 'test' ];

        // Expect.
        $this->expectException(ParamOtosetException::class);

        // Test.
        $file = new ModelPhpFile($settings, $model);
        $file->getTplUri('unknownTpl');
    }

    /**
     * Test if failure in finding template file will throw.
     *
     * @return void
     *
     * @depends testModelCreator
     */
    public function testIfFailToFindTemplateFileThrows() : void
    {

        // Lvd.
        $model    = new GirlModel();
        $settings = [ 'srcDir' => 'test' ];

        // Change dir to sabotage.
        chdir('vendor/');

        // Test.
        try {

            // Work.
            $file = new ModelPhpFile($settings, $model);
            $file->getTplUri(PhpFile::TPL_MODEL);

        } catch (Throwable $thr) {

            // Test.
            $this->assertEquals(get_class($thr), FileDonoexException::class);

        } finally {

            // Return to dir.
            chdir('../');
        }
    }
}
