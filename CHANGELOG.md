# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2023-12-28

### Added

- Add `mysql` driver for setting store.
- Add `mongodb` driver for setting store.
- Add `file` driver for cache setting.
- Add `redis` driver for cache setting.
- Add `setting:clear-cache` artisan command.
- Add exception for database connection.

### Changed

- Big update and remove all dependent classes in laravel before bootstrapping to use setting on anywhere files.

### Fixed

- Fixed issue with Laravel bootstrapping. [Issue Link](https://github.com/laravel/framework/issues/49346)

## [1.0.1] - 2023-12-12

### Added

- Added `has` method.
- Added Concern files and clean `LaravelSettingPro` class

### Changed

- Update & optimize and Refactor.
- Rename test directory to tests.

### Fixed

- No Fixed.

## [1.0.0] - 2023-12-09

### Added

- Initial release.

