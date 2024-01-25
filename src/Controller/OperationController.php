<?php

namespace App\Controller;

use App\Db;
use App\Dto\OperationForm;
use App\ErrorJsonResponse;
use App\SuccessJsonResponse;
use Monolog\Logger;

readonly class OperationController
{
    public function __construct(
        private Logger $logger,
        private Db     $db
    )
    {
    }

    public function getLastTenTransactions(): never
    {
        $lastTenTransactions = $this->db->getLastTenTransactions();
        echo new SuccessJsonResponse("Got last 10 operations", $lastTenTransactions);

        exit();
    }

    public function getCurrentOpenTransaction(int $id): never
    {
        $getOpenTransactionById = $this->db->getCurrentOpenTransactionById($id);
        echo new SuccessJsonResponse("Get open transaction info by ID", $getOpenTransactionById);
        exit();
    }

    public function deleteCurrentOperation(int $id): never
    {
        if ($this->db->deleteCurrentOperation($id) === true) {
            echo new SuccessJsonResponse("Deleted operation by ID $id", []);
        } else {
            echo new ErrorJsonResponse("Error deleting $id");
        }
        exit();
    }

    /**
     * @throws \Exception
     */
    public function createNewOperation(): never
    {
        $operationForm = OperationForm::createNewOperation();

        if ($this->db->createNewOperation($operationForm->amount, $operationForm->comment, $operationForm->isIncome)) {
            echo new SuccessJsonResponse("Created operation", []);
        } else {
            echo new ErrorJsonResponse("Error creating operation");
        }
        exit ();
    }

    public function totalSum(): never
    {
        $totalSum = $this->db->getTotalIncome();
        echo new SuccessJsonResponse("Total sum receive successfully", $totalSum);
        exit();
    }

    public function totalSumExpense(): never
    {
        $totalSumExpense = $this->db->getTotalExpenses();
        echo new SuccessJsonResponse("Total sum expenses received successfully", $totalSumExpense);
        exit();
    }

    public function searchByComment(): never
    {
        $operations = $this->db->getTransactionListByComment($_GET['query']);

        if (!empty($operations)) {
            echo new SuccessJsonResponse("Search by `{$_GET['query']}` completed", $operations);
        } else {
            echo new ErrorJsonResponse("Search by `{$_GET['query']}` not found");
        }
        exit();
    }
}

