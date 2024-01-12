<?php

namespace App\Dto;


use Exception;

readonly class RegisterForm
{
    public function __construct(
        public string $login,
        public string $password
    )
    {
    }

    /**
     * @throws Exception
     */
    public static function fromUserRequest(): self
    {
        $requestAsArray = json_decode(file_get_contents('php://input'), true);
        if (empty($requestAsArray['login'])) {
            throw new \Exception('Login is empty!');
        }
        if (empty($requestAsArray['password'])) {
            throw new \Exception('Password is empty!');
        }

        return new self($requestAsArray['login'], $requestAsArray['password']);
    }
}