<?php

namespace App\Controller;

use App\Db;
use App\Dto\OperationForm;
use App\ErrorJsonResponse;
use App\SuccessJsonResponse;
use Monolog\Logger;

class OperationController
{
    public function __construct(
        private readonly Logger $logger,
        private readonly Db     $db
    )
    {
    }

    public function getLastTenTransactions(): never
    {
        $lastTenTransactions = $this->db->getLastTenTransactions();
        echo new SuccessJsonResponse("Registered", $lastTenTransactions);

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

}

