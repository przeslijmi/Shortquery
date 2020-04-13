<?php declare(strict_types=1);

use Przeslijmi\Silogger\Silogger;

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
      'url'  => '127.0.0.1',
      'user' => 'shippable',
      'pass' => 'password',
      'db'   => 'shoq_test',
      'port' => 3306,
    ],
  ],
  // Test engine.
  'testWrong' => [
    'engine'   => 'mySql',
    'auth'   => [
      'url'  => 'wrong',
      'user' => 'wrong',
      'pass' => 'wrong',
      'db'   => 'wrong',
      'port' => 3306,
    ],
  ],

]);

Silogger::declare(
  'default',
  [
    'cli' => [
      'levels' => [
      ]
    ]
  ]
);
