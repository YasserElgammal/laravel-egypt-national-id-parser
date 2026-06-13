# Laravel Egypt National ID Parser

A Laravel package to parse and validate Egyptian National ID numbers, extracting details such as birth date, gender, and governorate.

## 📌 Features
- Validate Egyptian National ID numbers (including **Modulo 11 check digit verification**)
- Extract birth date, gender, and governorate into a fully typed DTO (`NationalIdData`)
- Custom Laravel Validation Rule
- Supports Arabic and English translations

## 📦 Installation

Require the package via Composer:

```sh
composer require yasser-elgammal/laravel-egypt-national-id-parser
```

Publish the configuration and translation files:

```sh
php artisan vendor:publish --tag=laravel-egypt-national-id-parser-config
php artisan vendor:publish --tag=laravel-egypt-national-id-parser-translations
```

## ⚙️ Configuration

The configuration file `config/national-id.php` allows customization of the package settings, including the default language.

## 🛠 Usage

### 1. Using Custom Validation Rule

The easiest way to use the package is through the provided validation rule:

```php
use YasserElgammal\LaravelEgyptNationalIdParser\Rules\EgyptianNationalId;

$request->validate([
    'national_id' => ['required', 'string', new EgyptianNationalId()],
]);
```

### 2. Parsing to Data Transfer Object (DTO)

You can parse an ID into a `NationalIdData` object which provides strongly-typed methods:

```php
use YasserElgammal\LaravelEgyptNationalIdParser\Facades\NationalId;

$idNumber = '29001010112341';
$data = NationalId::parse($idNumber);

if ($data) {
    echo $data->getBirthDate()->format('Y-m-d'); // Carbon instance
    echo $data->getAge(); // int
    echo $data->getGenderLabel(); // string (Male/Female)
    echo $data->getGovernorateLabel(); // string
    echo $data->getCheckDigit(); // string
}
```

To throw an exception if the ID is invalid instead of returning `null`:

```php
use YasserElgammal\LaravelEgyptNationalIdParser\Exceptions\InvalidNationalIdException;

try {
    $data = NationalId::parseOrFail($idNumber);
} catch (InvalidNationalIdException $e) {
    return response()->json(['errors' => $e->getErrors()], 422);
}
```

### 3. Validating an ID Number (Legacy Array Response)

If you prefer working with arrays:

```php
use YasserElgammal\LaravelEgyptNationalIdParser\Facades\NationalId;

$result = NationalId::validate($idNumber);

return response()->json([
    'status' => $result['status'], // boolean
    'data' => $result['data'] ?? null, // array of components
    'errors' => $result['errors'] ?? [] // array of validation errors
]);
```

You can also customize the returned language (default is 'en'):

```php
$result = NationalId::setLanguage('ar')->validate($idNumber);
```

## 📝 License
This package is open-source and licensed under the [MIT License](LICENSE.md).

## 🙌 Contributions
Contributions are welcome! Feel free to submit a pull request or report issues.

## 📬 Contact
For any questions, reach out via GitHub Issues or contact me directly.
