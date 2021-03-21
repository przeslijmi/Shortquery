<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery;

use PHPUnit\Framework\TestCase;
use Przeslijmi\Sexceptions\Sexception;
use Przeslijmi\Shortquery\Creator\PhpFile;
use Przeslijmi\Shortquery\Creator\PhpFile\Model as ModelPhpFile;
use Przeslijmi\Shortquery\Exceptions\Creator\ModelsInSchemaDonoexException;
use Przeslijmi\Shortquery\Exceptions\Creator\SchemaFileCorruptedException;
use Przeslijmi\Shortquery\Exceptions\Creator\SchemaFileDonoexException;
use Przeslijmi\Shortquery\Exceptions\Creator\SchemaMissingException;
use Przeslijmi\Shortquery\Exceptions\Creator\TemplateFileDonoexException;
use Przeslijmi\Shortquery\Exceptions\Creator\TemplateNameOtosetException;
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

        // Create.
        $md = new CreatorStarter();
        $md->run('../resources/schemaForTesting.php');

        // Test.
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
        $md->run('../resources/schemaForTesting.php');

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
        $this->expectException(TemplateNameOtosetException::class);

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
            $this->assertEquals(get_class($thr), TemplateFileDonoexException::class);

        } finally {

            // Return to dir.
            chdir('../');
        }
    }

    /**
     * Test if model creator throws when no schema is given.
     *
     * @return void
     */
    public function testIfModelCreatorThrows1() : void
    {

        // Create creator.
        $creator = new Creator();
        $creator->getParams()->setOperation('create');

        // Test.
        try {
            $creator->start();
        } catch (Sexception $sexc) {
            $this->assertTrue($sexc->hasInCauses(SchemaMissingException::class));
            return;
        }

        $this->fail('Method did return different Error than Sexception.');
    }

    /**
     * Test if model creator throws when nonexisting schema is given.
     *
     * @return void
     */
    public function testIfModelCreatorThrows2() : void
    {

        // Create creator.
        $creator = new Creator();
        $creator->getParams()->setOperation('create');
        $creator->getParams()->setParam('baseDir', 'nonexisting-Dir');
        $creator->getParams()->setParam('schemaUri', 'nonexisting-Uri');

        // Test.
        try {
            $creator->start();
        } catch (Sexception $sexc) {
            $this->assertTrue($sexc->hasInCauses(SchemaFileDonoexException::class));
            return;
        }

        $this->fail('Method did return different Error than Sexception.');
    }

    /**
     * Test if model creator throws when corrupted schema is given.
     *
     * @return void
     */
    public function testIfModelCreatorThrows3() : void
    {

        // Create creator.
        $creator = new Creator();
        $creator->getParams()->setOperation('create');
        $creator->getParams()->setParam('schema', false);

        // Test.
        try {
            $creator->start();
        } catch (Sexception $sexc) {
            $this->assertTrue($sexc->hasInCauses(SchemaFileCorruptedException::class));
            return;
        }

        $this->fail('Method did return different Error than Sexception.');
    }

    /**
     * Test if model creator throws when empty schema is given.
     *
     * @return void
     */
    public function testIfModelCreatorThrows4() : void
    {

        // Create creator.
        $creator = new Creator();
        $creator->getParams()->setOperation('create');
        $creator->getParams()->setParam('schema', []);

        // Test.
        try {
            $creator->start();
        } catch (Sexception $sexc) {
            $this->assertTrue($sexc->hasInCauses(ModelsInSchemaDonoexException::class));
            return;
        }

        $this->fail('Method did return different Error than Sexception.');
    }

    /**
     * Test if model creator throws when schema without models is given.
     *
     * @return void
     */
    public function testIfModelCreatorThrows5() : void
    {

        // Create creator.
        $creator = new Creator();
        $creator->getParams()->setOperation('create');
        $creator->getParams()->setParam('schema', [
            'settings' => [
                'src' => [
                    'Przeslijmi\Shortquery' => 'vendor\\przeslijmi\\shortquery\\src\\',
                ],
            ],
            'models' => [
            ],
        ]);

        // Test.
        try {
            $creator->start();
        } catch (Sexception $sexc) {
            $this->assertTrue($sexc->hasInCauses(ModelsInSchemaDonoexException::class));
            return;
        }

        $this->fail('Method did return different Error than Sexception.');
    }
}
