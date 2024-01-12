<?php

namespace App;

use Exception;
use PDO;
use PDOException;

class Db
{
    public function __construct(
        private readonly PDO    $pdo,
        private readonly Logger $logger
    )
    {
    }

    /**
     * @throws Exception
     */
    public function getUserByLoginAndPassword($login, $password): array|false
    {
        $sql = "SELECT * FROM users WHERE login = ?";
        $pdoStatement = $this->pdo->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
        $pdoStatement->execute([$login]);
        $userArray = $pdoStatement->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $userArray['password_hashed'])) {
            return $userArray;
        }

        throw new Exception("user password and stored hash not equal");
    }

    public function getUserIdByLogin(string $login): int
    {
        $sql = "SELECT * FROM users WHERE login = ?";
        $pdoStatement = $this->pdo->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
        $pdoStatement->execute([$login]);
        $userArray = $pdoStatement->fetch(PDO::FETCH_ASSOC);
        return $userArray['id'] ?? 0;
    }

    public function createNewUser(string $login, string $password): int
    {
        if ($this->getUserIdByLogin($login)) {
            throw new  Exception("User already exists");
        }
        try {
            $sql = "INSERT INTO users (login, password_hashed) VALUES (?, ?)";
            $pdoStatement = $this->pdo->prepare($sql);
            return $pdoStatement->execute([$login, password_hash($password, CRYPT_STD_DES)]);
        } catch (PDOException $exception) {
            return false;
        }
    }
}

