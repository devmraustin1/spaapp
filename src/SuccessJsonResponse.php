<?php

namespace App;

readonly class SuccessJsonResponse
{
    public function __construct(
        private string $message,
        private array  $data
    )
    {
    }

    public function __toString(): string
    {
        return json_encode([
            'success' => true,
            'message' => $this->message,
            'data' => $this->data
        ]);
    }
}