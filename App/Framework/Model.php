<?php

namespace App\Framework;

use App\Models\Database;
use App\Models\DB;

abstract class Model
{
    protected $fileName;
    protected array $errors = [];
    public function __construct(
        private Database $db
    ) {
    }

    protected function getFileName()
    {
        if ($this->fileName) {
            return $this->fileName;
        }

        $className = explode('\\', $this::class);
        $singularTableName = strtolower(array_pop($className));
        return DATA_PATH . $singularTableName . 's' . '.csv';
    }

    public function getTransactionData(): array
    {

        $transactions = $this->db->getDataFromFile($this->getFileName(), $this->extractTransaction);

        $totals = $this->getTotals($transactions);

        return [
          'transactions' => $transactions,
          'totals' => $totals
        ];
    }

    public function insertTransaction(array $transaction): bool|string
    {
        $this->validate($transaction);
        if ($this->errors) {
            return false;
        }


        $file = fopen($this->getFileName(), 'a');
        if ($file === false) {
            return false;
        }

        $rowData = $this->sanitizeTransaction($transaction);

        $written = fputcsv($file, $rowData);

        fclose($file);

        if ($written !== false) {
            return $this->getNameOfTransaction($transaction['checkNumber']);
        } else {
            return false;
        }
    }

    public function updateTransaction(array $transaction): bool|string
    {

        $this->validate($transaction);
        if ($this->errors) {
            return false;
        }


        $fileName = $this->getFileName();
        $file = fopen($fileName, 'r');
        if ($file === false) {
            return false;
        }

        if (!$this->handleTempFile()) {
            return false;
        }

        $tempFile = $this->tempFile;
        $tempFileName = $this->tempFileName;

        $checkNumber = (string)($transaction['checkNumber'] ?? '');
        $rowData = $this->sanitizeTransaction($transaction);
        $updated = false;
        $written = true;

        while (($row = fgetcsv($file)) !== false) {
            if (! $updated && ($row[1] ?? '') === $checkNumber) {
                $row = $rowData;
                $updated = true;
            }

            if (fputcsv($tempFile, $row) === false) {
                $written = false;
                break;
            }
        }

        fclose($file);
        if (!$this->closeTempFile($updated, $written)) {
            return false;
        }

        return $rowData;

    }

    public function deleteTransaction(int $transactionId)
    {
        $checkNumber = (string)$transactionId;
        $fileName = $this->getFileName();
        $file = fopen($fileName, 'r');
        if ($file === false) {
            return false;
        }


        if (!$this->handleTempFile()) {
            return false;
        }


        $tempFile = $this->tempFile;
        $tempFileName = $this->tempFileName;


        $deleted = false;
        $written = true;

        while (($row = fgetcsv($file)) !== false) {
            if (! $deleted && ($row[1] ?? '') === $checkNumber) {
                $deleted = true;
                continue;
            }

            if (fputcsv($tempFile, $row) === false) {
                $written = false;
                break;
            }
        }

        fclose($file);
        if (!$this->closeTempFile($deleted, $written)) {
            return false;
        }


        return true;
    }

    public function getNameOfTransaction(string $checkNumber): string
    {

        $itemSlug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $checkNumber), '-'));

        return $itemSlug;

    }

    public function updateDatabase(int $transactionId, array $transaction): bool
    {
        $sql = "UPDATE INTO transactions ";
        unset($transaction['id']);
        $keys = array_keys($transaction);
        array_walk($keys, function (&$value) {
            $value = "{$values} = ?";
        });
        $placeholders = implode($keys, ', ');
        $sql .= $placeholders;
        $sql .= " WHERE id = ?;";

        $db = new DB();
        $db->prepare($sql);

        $idx = 1;
        foreach ($transaction as $value) {

            $type = match($gettype($value)) {
                "boolean" => PDO::PARAM_BOOL,
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };

            $db->bindValue($idx++, $value, $type);
        }
        $db->bindValue($idx++, $transactionId, PDO::PARAM_INT);
        return $db->execute();

    }

    public function insertDatabase(array $transaction): bool
    {
        $columns = implode(', ', array_keys($transaction));
        $placeholders = implode(', ', array_fill(0, count($transaction), '?'));

        $sql = "INSERT INTO transactions ({$columns})
                VALUES ($placeholders)";

        $db = new DB();
        $db->prepare($sql);

        $idx = 1;
        foreach ($transaction as $value) {

            $type = match($gettype($value)) {
                "boolean" => PDO::PARAM_BOOL,
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };

            $db->bindValue($idx++, $value, $type);
        }
        return $db->execute();
    }

    public function getTotals(array $transactions): array
    {
        return $this->calculateTotals($transactions);
    }

    public function getAllTransactionData(): array
    {
        $files = $this->db->allFilesInfo();
        $transactions = [];
        foreach ($files as $file) {
            $transactions = array_merge($transactions, $db->getDataFromFile($file, $this->extractTransaction));
        }
        return $transactions;
    }

    public function getTransactionById(int $id)
    {
        $targetTransaction = $this->db->getDataById($id);
        if ($targetTransaction) {
            return $this->extractTransaction($targetTransaction);
        }
    }


    protected function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }

    protected function getErrors(): array
    {
        return $this->errors;
    }

    protected function validate(array $value): bool
    {
    }


    public function extractTransaction(array $transactionRow): array
    {
        [
            $date,
            $checkNumber,
            $description,
            $amount,
        ] = $transactionRow;
        // Replace all the $ and ',' characters in the amount
        $amount = floatval(str_replace([ '$', ',' ], '', $amount));

        return [
            'date'        => $date,
            'checkNumber' => $checkNumber,
            'description' => $description,
            'amount'      => $amount,
        ];

    }

    public function calculateTotals(array $transactions): array
    {
        $total = [ 'netTotal' => 0, 'totalIncome' => 0, 'totalExpenses' => 0 ];

        foreach ($transactions as $transaction) {
            $total['netTotal'] += $transaction['amount'];
            if ($transaction['amount'] >= 0) {
                $total['totalIncome'] += $transaction['amount'];
            } else {
                $total['totalExpenses'] += $transaction['amount'];
            }
        }

        return $total;
    }

    public function sanitizeTransaction(array $transaction)
    {
        return [
            $transaction['date'] ?? '',
            $transaction['checkNumber'] ?? '',
            $transaction['description'] ?? '',
            $transaction['amount'] ?? 0,
        ];
    }
}
