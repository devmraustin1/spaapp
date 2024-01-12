<?php

namespace App;

readonly class ErrorJsonResponse
{
    public function __construct(
        private int $message
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