<?php
declare(strict_types=1);

namespace App;

use App\Dto\OperationForm;
use Exception;
use Monolog\Logger;
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
    public function getUserByLoginAndPassword($login, $password): array
    {
        $sql = "SELECT * FROM users WHERE login = ?";
        $pdoStatement = $this->pdo->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
        $pdoStatement->execute([$login]);
        $userArray = $pdoStatement->fetch(PDO::FETCH_ASSOC);

        if (empty($userArray)) {
            throw new Exception("user with $login not found");
        }

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

    /**
     * @throws Exception
     */
    public function createNewUser(string $login, string $password): int
    {
        if ($this->getUserIdByLogin($login)) {
            throw new Exception("User already exists");
        }

        $sql = "INSERT INTO users (login, password_hashed) VALUES (?, ?)";
        $pdoStatement = $this->pdo->prepare($sql);
        $pdoStatement->execute([$login, password_hash($password, CRYPT_STD_DES)]);
        return $this->getUserIdByLogin($login);
    }

    public function getLastTenTransactions(): array
    {
        return $this->pdo->query("SELECT * FROM operations ORDER by id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCurrentOpenTransactionById(int $id): array
    {
        return $this->pdo->query("SELECT * FROM operations WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteCurrentOperation(int $id): bool
    {
        return $this->pdo->query("DELETE FROM operations WHERE id = $id")->execute();
    }

    public function createNewOperation(int $amount, string $comment, bool $isIncome): bool
    {
        $sql = "INSERT INTO operations (amount, comment, is_income) VALUES (?, ?, ?)";
        $pdoStatement = $this->pdo->prepare($sql);
        return $pdoStatement->execute([$amount, $comment, (int)$isIncome]);
    }

    public function getTotalIncome()
    {
        return $this->pdo->query("SELECT SUM(AMOUNT) AS TOTAL_SUM_INCOME FROM operations WHERE is_income = 1;")->fetch(PDO::FETCH_ASSOC);
    }

    public function getTotalExpenses()
    {
        return $this->pdo->query("SELECT SUM(AMOUNT) AS TOTAL_SUM_EXPENSE FROM operations WHERE is_income = 0;")->fetch(PDO::FETCH_ASSOC);
    }

    public function getTransactionListByComment(string $search): array
    {
        $sql = "SELECT * FROM operations WHERE comment LIKE :search";
        $sth = $this->pdo->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
        $sth->execute(['search' => "%$search%"]);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}


