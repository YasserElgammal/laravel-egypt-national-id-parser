# Laravel Egypt National ID Parser

A Laravel package to parse and validate Egyptian National ID numbers, extracting details such as birth date, gender, and governorate.

## 📌 Features
- Validate Egyptian National ID numbers
- Extract birth date, gender, and governorate
- Supports Arabic and English translations

## 📦 Installation

Require the package via Composer:

```sh
composer require yasserelgammal/laravel-egypt-national-id-parser
```

Publish the configuration and translation files:

```sh
php artisan vendor:publish --tag=laravel-egypt-national-id-parser-config
php artisan vendor:publish --tag=laravel-egypt-national-id-parser-translations
```

## ⚙️ Configuration

The configuration file `config/national-id.php` allows customization of the package settings, including the default language.

## 🛠 Usage

### Validating an ID Number

```php
use YasserElgammal\LaravelEgyptNationalIdParser\Facades\NationalId;

$idNumber = '00000000000000';
$result = NationalId::validate($idNumber);
```

You can Also Customize returned lang, default lang is 'english':

```php
$result = NationalId::setLanguage('ar')->validate($idNumber)
```

```php
return response()->json([
    d'status' => $result['status'],
    'message' => $result['status'] ? 'Valid ID' : 'Invalid ID',
    'data' => $result['data'] ?? null,
    'errors' => $result['errors'] ?? []
]);
```

## 📝 License
This package is open-source and licensed under the [MIT License](LICENSE.md).

## 🙌 Contributions
Contributions are welcome! Feel free to submit a pull request or report issues.

## 📬 Contact
For any questions, reach out via GitHub Issues or contact me directly.
