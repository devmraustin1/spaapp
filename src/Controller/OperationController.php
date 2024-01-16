<?php

namespace App\Controller;

use App\Db;
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


}