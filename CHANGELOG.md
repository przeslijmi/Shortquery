# Changelog

## v1.5.0 - 2021-03-21

- New: Added multiple new Exceptions to use in code.
- Change: Changed all `Exceptions` directly from `Preslijmi\Sexception` tool to own `Exceptions`.
- Change: Logging turned off for all queries as absolute solution (because of efficiency and RODO).

## v1.4.0 - 2021-03-14

- New: Added param `multipleResults` (default `true`) to `getGroupedByField` in `Collection`.
- Change: `FieldValueInproperException` returns `model` and `modelName` infos.
- Fix: Small bugs in `CacheByKey`.
- Fix: `CollectionCore` error.
- Temp: Logging turned off for `Select` queries.

## v1.3.0 - 2020-10-11

- New: Added `->saveQuery()`, `->createQuery()` and `->updateQuery()` for Instance class - they return query contents for all three operations.
- New: Added auto primary key counter when adding collections - **do not use in production yet**!
- New: Added `->markNotTakenOut()` method for `CacheByKey`.
- Change: Multirecords insert query has now every new record in a new line.
- Fix: Proper deletion created for `makeSplittedContentsAnalogousToArray` tool

## v1.2.2 - 2020-07-03

- Change: `SelectQuery` `->readBy()` method logs warning when two records with the same key are found.
- Change: Removed unused tag `@since` from docs.

## v1.2.1 - 2020-06-18

- Fix: after sniffing.

## v1.2.0 - 2020-06-17

- New: Added `->lengthReal()` for `Collection` witch returns real number of records (etc. minus those that are declared to be deleted).
- New: Added `->getFieldByNameIfExists()` for `Model`.
- New: Added `->getData()` and `->freeMemory()` for `CacheByKey`.
- New: Added `CacheElementMissingException` for `CacheByKey`.
- New: Added `ModelsInSchemaDonoexException`, `SchemaFileCorruptedException`, `SchemaFileDonoexException`, `SchemaMissingException` for `Creator`.
- Change: `Creator` on `PhpFile` converts unknown classes to `UnknownClass` to prevent errors when `ob_start` is working.
- Change: extra fields can be now used in `CacheByKey` and thay are not injected to final Instances on creation (if the field does not exists - it's value is ignored on creation).
- Change: `CacheByKey` keeps indexes of keys not as values in array but as keys in array which speeds up process.
- Fix: colliding namespaces on instance core are fixed.

## v1.1.0 - 2020-05-21

- New: Added children delivering by `CacheByKey` class.
- New: Added to `Collection` methods: `update()`, `create()`, `delete()` (as second param) and `save()` (as first param) methods param as boolean. If set to true queries will not be berformed but only send to debug log.
- New: Added to `Instance` methods: `create()`, `update()`, `save()` and `delete()` methods first param as boolean. If set to true queries will not be berformed but only send to debug log.
- New: Added `getModelOtherThan()` method to `Relation` that return model other then given in param (it is useful when you don't what relation `from` or `to` model but just "the other one").
- New: Added service for multiquery queries.
- New: Added `readMultipleBy()` method for `SelectQuery`.
- New: Added better service for fields from relation in `SelectQuery`.
- Fix: Fixed `makeSplittedContentsAnalogousToArray()` problem with empty collections.
- Fix: Fixed `JsonField` service.

## v1.0.0 - 2020-04-14

- New: Offical release.
