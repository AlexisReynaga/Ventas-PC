<?php

namespace App\Services\ExternalApi\Exceptions;

use Exception;

class ExternalApiException extends Exception
{
    protected array $payload = [];

    public function __construct(string $message, int $code = 0, array $payload = [])
    {
        parent::__construct($message, $code);
        $this->payload = $payload;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
