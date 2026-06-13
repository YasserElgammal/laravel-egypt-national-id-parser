<?php

declare(strict_types=1);

namespace YasserElgammal\LaravelEgyptNationalIdParser\Exceptions;

use Exception;

class InvalidNationalIdException extends Exception
{
    private array $errors;

    public function __construct(string $message = "Invalid Egyptian National ID", array $errors = [], int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
