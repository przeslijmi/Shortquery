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
 * ShortQuery Model definition for Girl.
 */
class GirlModel extends Model
{

    /**
     * Holder of model (to prevent multicreation).
     *
     * @var GirlModel
     */
    private static $instance;

    /**
     * Retrieves only one instance to prevent multicreation.
     *
     * @return GirlModel
     */
    public static function getInstance() : GirlModel
    {

        if (is_null(self::$instance) === true) {
            self::$instance = new GirlModel();
        }

        return self::$instance;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {

        // Define Model.
        $this->setName('girls');
        $this->setDatabases('test');
        $this->setNamespace('Przeslijmi\Shortquery\ForTests\Models');
        $this->setInstanceClassName('Girl');
        $this->setCollectionClassName('Girls');

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
            ( new SetField('webs', false) )
                ->setValues('sc', 'pt', 'is', 'fb')
                ->setDict('main', 'snapchat', 'pinterest', 'instagram', 'facebook')
                ->setPk(false)
        );

        // Define Relations of Model.
        $this->addRelation(
            ( new HasManyRelation('cars') )
                ->setFieldFrom('pk')
                ->setModelTo('Przeslijmi\Shortquery\ForTests\Models\Core\CarModel')
                ->setFieldTo('owner_girl')
        );
        $this->addRelation(
            ( new HasManyRelation('fastCars') )
                ->setFieldFrom('pk')
                ->setModelTo('Przeslijmi\Shortquery\ForTests\Models\Core\CarModel')
                ->setFieldTo('owner_girl')
                ->addRule('is_fast', 'yes')
        );
    }
}
