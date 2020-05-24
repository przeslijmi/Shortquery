# Changelog

## v1.1.0 - 2020-05-21

- Added children delivering by `CacheByKey` class.
- Added to `Collection` methods: `update()`, `create()`, `delete()` (as second param) and `save()` (as first param) methods param as boolean. If set to true queries will not be berformed but only send to debug log.
- Added to `Instance` methods: `create()`, `update()`, `save()` and `delete()` methods first param as boolean. If set to true queries will not be berformed but only send to debug log.
- Added `getModelOtherThan()` method to `Relation` that return model other then given in param (it is useful when you don't what relation `from` or `to` model but just "the other one").
- Added service for multiquery queries.
- Added `readMultipleBy()` method for `SelectQuery`.
- Added better service for fields from relation in `SelectQuery`.
- Fixed `makeSplittedContentsAnalogousToArray()` problem with empty collections.
- Fixed `JsonField` service.

## v1.0.0 - 2020-04-14

- Offical release.
