<?php declare(strict_types=1);

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

// Define configs.
return [
  'settings' => [
    'src' => [
      'Przeslijmi\Shortquery' => '../src/',
    ]
  ],
  'models' => [
    (new Model()) // girls
      ->setName('girls')
      ->setDatabases('test')
      ->setNamespace('Przeslijmi\Shortquery\ForTests\Models')
      ->setInstanceClassName('Girl')
      ->setCollectionClassName('Girls')
      ->addField(
        (new IntField('pk'))
          ->setMaxLength(11)
          ->setPk(true)
      )
      ->addField(
        (new VarCharField('name'))
          ->setMaxLength(45)
      )
      ->addField(
        (new SetField('webs'))
          ->setValues('sc', 'pt', 'is', 'fb')
          ->setMainDict('snapchat', 'pinterest', 'instagram', 'facebook')
      )
      ->addRelation(
        (new HasManyRelation('cars'))
          ->setModelFrom('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel')
          ->setFieldFrom('pk')
          ->setModelTo('Przeslijmi\Shortquery\ForTests\Models\Core\CarModel')
          ->setFieldTo('owner_girl')
      )
      ->addRelation(
        (new HasManyRelation('fastCars'))
          ->setModelFrom('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel')
          ->setFieldFrom('pk')
          ->setModelTo('Przeslijmi\Shortquery\ForTests\Models\Core\CarModel')
          ->setFieldTo('owner_girl')
          ->addLogicsSyntax('->addRule(\'is_fast\', \'yes\')')
      ),
    (new Model()) // cars
      ->setName('cars')
      ->setDatabases('test')
      ->setNamespace('Przeslijmi\Shortquery\ForTests\Models')
      ->setInstanceClassName('Car')
      ->setCollectionClassName('Cars')
      ->addField(
        (new IntField('pk'))
          ->setMaxLength(11)
          ->setPk(true)
      )
      ->addField(
        (new IntField('owner_girl'))
          ->setMaxLength(11)
      )
      ->addField(
        (new EnumField('is_fast'))
          ->setValues('no', 'yes')
          ->setMainDict('nie', 'tak')
      )
      ->addField(
        (new VarCharField('name'))
          ->setMaxLength(45)
      )
      ->addRelation(
        (new HasOneRelation('oneOwnerGirl'))
          ->setModelFrom('Przeslijmi\Shortquery\ForTests\Models\Core\CarModel')
          ->setFieldFrom('owner_girl')
          ->setModelTo('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel')
          ->setFieldTo('pk')
      )
      ->addRelation(
        (new HasOneRelation('oneOwnerGirlWithSnapchat'))
          ->setModelFrom('Przeslijmi\Shortquery\ForTests\Models\Core\CarModel')
          ->setFieldFrom('owner_girl')
          ->setModelTo('Przeslijmi\Shortquery\ForTests\Models\Core\GirlModel')
          ->setFieldTo('pk')
          ->addLogicsSyntax('->addRule([ \'inset\', [ \'sc\', \'`webs`\' ]], \'eq\', true)')
      ),
    (new Model()) // things
      ->setName('things')
      ->setDatabases('test')
      ->setNamespace('Przeslijmi\Shortquery\ForTests\Models')
      ->setInstanceClassName('Thing')
      ->setCollectionClassName('Things')
      ->addField(
        (new IntField('pk'))
          ->setMaxLength(11)
          ->setPk(true)
      )
      ->addField(
        (new VarCharField('name'))
          ->setMaxLength(45)
      )
      ->addField(
        (new JsonField('json_data'))
      ),
  ]
];
