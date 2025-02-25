<?php

namespace YasserElgammal\LaravelEgyptNationalIdParser\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;
use InvalidArgumentException;

class NationalIdValidator
{
    private string $lang;

    public function __construct(string $lang = null)
    {
        $this->lang = $lang ?? config('national-id.default_language', 'en');

        if (!in_array($this->lang, ['en', 'ar'])) {
            throw new InvalidArgumentException('Language must be either "en" or "ar"');
        }
    }

    public function setLanguage(string $lang): self
    {
        if (!in_array($lang, ['en', 'ar'])) {
            throw new InvalidArgumentException('Language must be either "en" or "ar"');
        }
        $this->lang = $lang;
        return $this;
    }
    public function validate(string $idNumber): array
    {
        $errors = [];

        if (!$this->isValidIdFormat($idNumber)) {
            return [
                'status' => false,
                'errors' => [$this->trans('validation.digits')]
            ];
        }

        $components = $this->extractComponents($idNumber);

        if (!$components) {
            return [
                'status' => false,
                'errors' => [$this->trans('validation.invalid_format')]
            ];
        }

        if (!$this->isValidBirthDate($components['birth_date'])) {
            $errors[] = $this->trans('validation.invalid_date');
        }

        if (!$this->isValidGovernorate($components['governorate_code'])) {
            $errors[] = $this->trans('validation.invalid_governorate');
        }

        return [
            'status' => empty($errors),
            'errors' => $errors,
            'data' => empty($errors) ? $this->formatResponseData($components) : null
        ];
    }

    private function extractComponents(string $idNumber): ?array
    {
        $centuryMap = ['1' => 1800, '2' => 1900, '3' => 2000];
        $century = $idNumber[0];

        if (!isset($centuryMap[$century])) {
            return null;
        }

        $fullYear = $centuryMap[$century] + (int)substr($idNumber, 1, 2);
        $month = (int)substr($idNumber, 3, 2);
        $day = (int)substr($idNumber, 5, 2);

        if (!checkdate($month, $day, $fullYear)) {
            return null;
        }

        return [
            'birth_date' => Carbon::createFromFormat('Y-m-d', "$fullYear-$month-$day"),
            'governorate_code' => substr($idNumber, 7, 2),
            'sequence' => substr($idNumber, 9, 4),
            'check_digit' => substr($idNumber, 13, 1)
        ];
    }

    private function formatResponseData(array $components): array
    {
        return [
            'birth_date' => $components['birth_date']->format('Y-m-d'),
            'age' => $components['birth_date']->age,
            'gender' => [
                'code' => $this->getGenderCode($components['sequence']),
                'label' => $this->trans('gender.' . $this->getGenderCode($components['sequence']))
            ],
            'governorate' => [
                'code' => $components['governorate_code'],
                'label' => $this->trans('cities.' . $components['governorate_code'])
            ],
            'sequence' => $components['sequence'],
            'check_digit' => $components['check_digit']
        ];
    }


    private function isValidIdFormat(string $idNumber): bool
    {
        return preg_match('/^\d{14}$/', $idNumber);
    }

    private function isValidBirthDate(Carbon $date): bool
    {
        return $date->isValid() && $date->lte(Carbon::now());
    }

    private function isValidGovernorate(string $code): bool
    {
        return Lang::has('laravel-egypt-national-id-parser::messages.cities.' . $code, $this->lang);
    }

    private function getGenderCode(string $sequence): string
    {
        return intval(substr($sequence, -1)) % 2 === 1 ? 'male' : 'female';
    }

    private function trans(string $key, array $replace = []): string
    {
        return Lang::get('laravel-egypt-national-id-parser::messages.' . $key, $replace, $this->lang);
    }

    public function getCities(): array
    {
        $cities = Lang::get('laravel-egypt-national-id-parser::messages.cities', [], $this->lang);
        $formatted = [];

        foreach ($cities as $code => $name) {
            if ($code === 'unknown') continue;
            $formatted[] = [
                'code' => $code,
                'name' => $name
            ];
        }

        return $formatted;
    }
}
