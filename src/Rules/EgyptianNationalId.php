<?php

declare(strict_types=1);

namespace YasserElgammal\LaravelEgyptNationalIdParser\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;
use YasserElgammal\LaravelEgyptNationalIdParser\Services\NationalIdValidator;

class EgyptianNationalId implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('The :attribute must be a valid string.');
            return;
        }

        /** @var NationalIdValidator $validator */
        $validator = app('national-id');
        $result = $validator->validate($value);

        if (!$result['status']) {
            foreach ($result['errors'] as $error) {
                $fail($error);
            }
        }
    }
}
