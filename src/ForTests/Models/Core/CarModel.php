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
 * ShortQuery Model definition for Car.
 */
class CarModel extends Model
{

    /**
     * Holder of model (to prevent multicreation).
     *
     * @var CarModel
     */
    private static $instance;

    /**
     * Retrieves only one instance to prevent multicreation.
     *
     * @return CarModel
     */
    public static function getInstance() : CarModel
    {

        if (is_null(self::$instance) === true) {
            self::$instance = new CarModel();
        }

        return self::$instance;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {

        // Define Model.
        $this->setName('cars');
        $this->setDatabases('test');
        $this->setNamespace('Przeslijmi\Shortquery\ForTests\Models');
        $this->setInstanceClassName('Car');
        $this->setCollectionClassName('Cars');

        // Define Fields of Model.
        $this->addField(
            ( new IntField('pk', true) )
                ->setMaxLength(11)
                ->setPk(true)
        );
        $this->addField(
            ( new IntField('owner_girl', false) )
                ->setMaxLength(11)
                ->setPk(false)
        );
        $this->addField(
            ( new EnumField('is_fast', false) )
                ->setValues('no', 'yes')
                ->setDict('main', 'nie', 'tak')
                ->setPk(false)
        );
        $this->addField(
            ( new VarCharField('name', false) )
                ->setMaxLength(45)
                ->setPk(false)
        );

        // Define Relations of Model.
        $this->addRelation(
            ( new HasOneRelation('oneOwnerGirl') )
                ->setFieldFrom('owner_girl')
                ->setModelTo('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel')
                ->setFieldTo('pk')
        );
        $this->addRelation(
            ( new HasOneRelation('oneOwnerGirlWithSnapchat') )
                ->setFieldFrom('owner_girl')
                ->setModelTo('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel')
                ->setFieldTo('pk')
                ->addRule([ 'inset', [ 'sc', '`webs`' ]], 'eq', true)
        );
    }
}
