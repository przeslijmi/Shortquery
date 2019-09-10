<?php declare(strict_types=1);

define('PRZESLIJMI_SHORTQUERY_ENGINES', [
  'mySql' => [
    'class'       => 'Przeslijmi\Shortquery\Engine\MySql',
    'createQuery' => 'Przeslijmi\Shortquery\Engine\MySql\Queries\InsertQuery',
    'readQuery'   => 'Przeslijmi\Shortquery\Engine\MySql\Queries\SelectQuery',
    'updateQuery' => 'Przeslijmi\Shortquery\Engine\MySql\Queries\UpdateQuery',
    'deleteQuery' => 'Przeslijmi\Shortquery\Engine\MySql\Queries\DeleteQuery',
  ],
]);

define('PRZESLIJMI_SHORTQUERY_DATABASES', [

  // Test engine.
  'test' => [
    'engine'   => 'mySql',
    'auth'   => [
      'url'  => 'localhost',
      'user' => 'user',
      'pass' => 'password',
      'db'   => 'db_name',
      'port' => 3306,
    ],
  ],

]);
