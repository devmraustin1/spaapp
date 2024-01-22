<?php
declare(strict_types=1);

namespace App\Dto;

readonly class OperationForm
{
    public function __construct(
        public int    $amount,
        public string $comment,
        public bool   $isIncome,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public static function createNewOperation(): self
    {
        $newOperationData = json_decode(file_get_contents('php://input'), true);

        if (empty($newOperationData['amount'])) {
            throw new \Exception('Amount is empty!');
        }

        $amountOperationData = $newOperationData['amount'];

        if (!is_numeric($amountOperationData)) {
            throw new \Exception('Amount should ne numbers only!');
        }

        if (empty($newOperationData['comment'])) {
            throw new \Exception('Comment is empty!');
        }

        if (!is_int($newOperationData['is_income'])) {
            throw new \Exception('Choose either "income" or "expense"');
        }

        return new self (
            (int)$amountOperationData,
            $newOperationData['comment'],
            (bool)$newOperationData['is_income']
        );
    }
}