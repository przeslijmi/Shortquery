<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\ForTests;

use Przeslijmi\Shortquery\Creator;
use Przeslijmi\Shortquery\Data\Field\DateField;
use Przeslijmi\Shortquery\Data\Field\DecimalField;
use Przeslijmi\Shortquery\Data\Field\EnumField;
use Przeslijmi\Shortquery\Data\Field\IntField;
use Przeslijmi\Shortquery\Data\Field\JsonField;
use Przeslijmi\Shortquery\Data\Field\SetField;
use Przeslijmi\Shortquery\Data\Field\VarCharField;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Data\Relation\HasManyRelation;
use Przeslijmi\Shortquery\Data\Relation\HasOneRelation;

class CreatorStarter
{

    private $overwriteNonCore = true;
    private $overwriteCore = true;

    public function setOverwriteNonCore(bool $overwriteNonCore) : self
    {

        $this->overwriteNonCore = $overwriteNonCore;

        return $this;
    }

    public function setOverwriteCore(bool $overwriteCore) : self
    {

        $this->overwriteCore = $overwriteCore;

        return $this;
    }

    public function run(string $schemaFileName)
    {

        $schemaDir  = rtrim(str_replace('/', '\\', __DIR__), '\\');
        $schemaDir  = substr($schemaDir, 0, strrpos($schemaDir, '\\'));
        $schemaDir  = substr($schemaDir, 0, strrpos($schemaDir, '\\'));
        $schemaDir .= '\\config\\';

        $schemaUri = $schemaDir . $schemaFileName;

        $creator = new Creator();
        $creator->getParams()->setOperation('create');
        $creator->getParams()->setParam('schemaUri', $schemaUri);
        $creator->getParams()->setParam('overwriteNonCore', $this->overwriteNonCore);
        $creator->getParams()->setParam('overwriteCore', $this->overwriteCore);
        $creator->start();
    }

    private function getStandardSchemaModels() : array
    {

        return [
        ];
    }
}
