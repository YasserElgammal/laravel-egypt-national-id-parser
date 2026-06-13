<?php

declare(strict_types=1);

namespace YasserElgammal\LaravelEgyptNationalIdParser\Data;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class NationalIdData implements Arrayable
{
    public function __construct(
        private readonly Carbon $birthDate,
        private readonly string $genderCode,
        private readonly string $genderLabel,
        private readonly string $governorateCode,
        private readonly string $governorateLabel,
        private readonly string $sequence,
        private readonly string $checkDigit
    ) {
    }

    public function getBirthDate(): Carbon
    {
        return clone $this->birthDate;
    }

    public function getAge(): int
    {
        return $this->birthDate->age;
    }

    public function getGenderCode(): string
    {
        return $this->genderCode;
    }

    public function getGenderLabel(): string
    {
        return $this->genderLabel;
    }

    public function getGovernorateCode(): string
    {
        return $this->governorateCode;
    }

    public function getGovernorateLabel(): string
    {
        return $this->governorateLabel;
    }

    public function getSequence(): string
    {
        return $this->sequence;
    }

    public function getCheckDigit(): string
    {
        return $this->checkDigit;
    }

    public function toArray(): array
    {
        return [
            'birth_date' => $this->birthDate->format('Y-m-d'),
            'age' => $this->getAge(),
            'gender' => [
                'code' => $this->genderCode,
                'label' => $this->genderLabel,
            ],
            'governorate' => [
                'code' => $this->governorateCode,
                'label' => $this->governorateLabel,
            ],
            'sequence' => $this->sequence,
            'check_digit' => $this->checkDigit,
        ];
    }
}
