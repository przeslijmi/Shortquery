<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\ForTests\Models\Core;

use Przeslijmi\Shortquery\Data\Field\DateField;
use Przeslijmi\Shortquery\Data\Field\DecimalField;
use Przeslijmi\Shortquery\Data\Field\EnumField;
use Przeslijmi\Shortquery\Data\Field\IntField;
use Przeslijmi\Shortquery\Data\Field\JsonField;
use Przeslijmi\Shortquery\Data\Field\VarCharField;
use Przeslijmi\Shortquery\Data\Field\SetField;
use Przeslijmi\Shortquery\Data\Model;
use Przeslijmi\Shortquery\Data\Relation\HasManyRelation;
use Przeslijmi\Shortquery\Data\Relation\HasOneRelation;

/**
 * ShortQuery Model definition for Thing.
 */
class ThingModel extends Model
{

    /**
     * Holder of model (to prevent multicreation).
     *
     * @var ThingModel
     */
    private static $instance;

    /**
     * Retrieves only one instance to prevent multicreation.
     *
     * @return ThingModel
     */
    public static function getInstance() : ThingModel
    {

        if (is_null(self::$instance) === true) {
            self::$instance = new ThingModel();
        }

        return self::$instance;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {

        // Define Model.
        $this->setName('things');
        $this->setDatabases('test');
        $this->setNamespace('Przeslijmi\Shortquery\ForTests\Models');
        $this->setInstanceClassName('Thing');
        $this->setCollectionClassName('Things');

        // Define Fields of Model.
        $this->addField(
            ( new IntField('pk', true) )
                ->setMaxLength(11)
                ->setPk(true)
        );
        $this->addField(
            ( new VarCharField('name', false) )
                ->setMaxLength(45)
                ->setPk(false)
        );
        $this->addField(
            ( new JsonField('json_data', false) )
                ->setPk(false)
        );
    }
}
