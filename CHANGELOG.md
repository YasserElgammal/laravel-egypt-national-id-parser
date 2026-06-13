# Changelog

All notable changes to `laravel-egypt-national-id-parser` will be documented in this file.

## [2.0.0] - 2026-06-13

### Added
- **Modulo 11 Check Digit Algorithm:** Added mathematical validation for the 14th check digit to ensure IDs are genuinely valid, not just structurally correct.
- **DTO Support (`NationalIdData`):** Added a `parse()` method that returns a strongly-typed Data Transfer Object containing all extracted fields (`getBirthDate()`, `getAge()`, etc.).
- **Laravel Custom Validation Rule:** Added `EgyptianNationalId` custom rule for seamless integration with Laravel's Form Requests and `$request->validate()`.
- **Exception Handling:** Added `parseOrFail()` method which throws a custom `InvalidNationalIdException` when an invalid ID is provided.
- **Testing:** Implemented automated test suite using PHPUnit and `orchestra/testbench`.
- **Translations:** Added translations for `invalid_check_digit` error.

### Fixed
- Added missing Composer dependencies (`illuminate/support`, `illuminate/validation`, `nesbot/carbon`) to `composer.json`.

### Changed
- Refactored `NationalIdValidator` logic to strictly type all methods.
- Enforced `declare(strict_types=1);` in core classes.

## [1.0.0] - 2024-01-01

### Added
- Initial release.
- Basic validation of Egyptian National ID structure and length.
- Extraction of basic data (birth date, governorate, gender).
