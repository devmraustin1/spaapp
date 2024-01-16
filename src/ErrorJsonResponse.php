<?php

namespace App;

readonly class ErrorJsonResponse
{
    public function __construct(
        private string $message
    )
    {
    }

    public function __toString(): string
    {
        return json_encode([
            'false' => false,
            'message' => $this->message
        ]);
    }
}