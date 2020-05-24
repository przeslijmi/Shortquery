# Przeslijmi Shortquery - my PDO approach

[![Run Status](https://api.shippable.com/projects/5e4da235f1aab60006271a7b/badge?branch=master)]()

[![Coverage Badge](https://api.shippable.com/projects/5e4da235f1aab60006271a7b/coverageBadge?branch=master)]()


## Table of contents

  1. [Usage examples](#usage-examples)
  1. [Defining data structure](#defining-data-structure)


## Usage examples

There are three (plus one) ways to use Shoq as PDO.
  1. [As instance reader](#as-instance-reader)
  1. [As collection reader](#as-collection-reader)
  1. As direct query sender
  1. **In future** as final short query own syntax

### As instance reader

```php
$girl = new Girl();
$girl->setPk(1);
$girl->read();
```

Above code connects to the proper engine and using defined schema (data structure) creates PHP Object `Girl` with defined properties.

It is now possible to get properties:

```php
$girl->getName(); // which returns her name
$girl->getWebs(); // which returns her social websites
```

It is possible to change her properties:

```php
$girl->setName('Lucia');
$girl->setWebs('fb,in');
$girl->save();
```

It is possible to create new instance:

```php
$girl = new Girl();
$girl->setName('Anie');
$girl->setWebs('fb');
$girl->save();
```

When this model has a relation defined in data structure schema you can read it to:
```php
$girl = new Girl();
$girl->setPk(1);
$girl->expandCars(); // one to many relation
$girl->read();
echo count($girl->getCars()->get()); // will echo '2' id this girl have two cars.
```

### As collection reader

This will read in from data engine and create PHP instances in object all Girls:

```php
$girls = new Girls();
$girls->read();
```

You can read only slice of records and ordered:

```php
$girls->readOrderedBy('name', 0, 3);
```

You can add logics to reading. Below is the example of two rules:
  - name is not 'Adriana', and
  - name is not 'test' or is not 'testEither'.

```php
$girls = new Girls();
$girls->getLogics()->addRuleNeq('name', 'Adriana');
$girls->getLogics()->addLogicOr([ 'name', 'neq', 'test' ], [ 'name', 'neq', 'testEither' ]);
```

## Defining data structure

In order to use models you have to define them. This is then used to create PHP Class files in `Creator` process. You can afterwards enhance created PHP Classes.

There are two ways to perform this action.

  1. Creating schema definition file and calling `modelsCreator.php` from command line.
  1. Creating schema definition file and calling `Creator` php class from other php class.

In both ways definition schema has to be created properly. See example at `resources/schemaForTesting.php`. Definition schema consists of two elements: `settings` (which has metadata) and `models` (which has list of models).

Element `settings` has to be an array with one key `src` beeing also an array in which all uris to all namespaces are defined. If you will use namespace `Vendor\App` you have to define here in which location models of this namespace are to be kept.

Element `models` is array that has one child for every model that has to be created.

**ATTENTION** All relative paths used here has to relate to schema file location itself.

### Data structure creation using command line

  1. Create PHP file for schema definition somewhere in your app (for eq. in `resources/` dir).
  1. Prepare its contents basing on instructions above.
  1. Open command line, bash, etc.
  1. Change to directory `bin/` of `ShortQuery` in which `modelsCreator.php` file is located.
  1. Run command as shown below.

```
php modelsCreator.php create --bootstrap "../../../../bootstrap.php" --schemaUri "../resources/schemaForTesting.php"
```

Where:
  - `--bootstrap` param is a relative location to bootstrap file (which calls vendor autoloader for eg.),
  - `--schemaUri` param is a relative location to settings file.

**ATTENTION** Both relative locations relate to `modelsCreator.php` file location.

### Data structure creation using PHP

```php
$md = new CreatorStarter();
$md->run('resources/schemaForTesting.php');
```

**ATTENTION** Relative location refer to current working directory of application

When using this approach you can define two extra settings.

```php
$md->setOverwriteCore(true);
$md->setOverwriteNonCore(false);
```

First toggles overwriting existing core files. This is set to `true` as default. Core files should never be changed to let shortquery do any changes depending on application evolution or data structure evolution.

Second toggles overwriting existing non-core files. This is set to `false` as default and it is very dangereous to set it to `true`. Non core files can be changed during development. They can be delivaring extra methods from data structure objects written by hand for this particular Model. Overwriting these files can erase some part of work and should not be done until you're absolutely sure what you're doing.
