<?php

namespace App\Models;

use App\Framework\Model;

class Transaction extends Model
{
    protected $fileName =
        DATA_PATH . 'transactions.csv';


    protected function validate(array $value): void
    {

        $date = trim($value['date'] ?? '');
        $description = trim($value['description'] ?? '');
        $amount = trim((string)($value['amount'] ?? ''));
        $checkNumber = trim((string)($value['checkNumber'] ?? ''));

        if ($date === '') {
            $this->addError('date', 'Date is required.');
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $this->addError('date', 'Date must be valid.');
        } else {
            [$year, $month, $day] = array_map('intval', explode('-', $date));
            if (!checkdate($month, $day, $year)) {
                $this->addError('date', 'Date must be valid.');
            }
        }

        if ($checkNumber !== '' && !ctype_digit($checkNumber)) {
            $this->addError('checkNumber', 'Check number must contain only digits.');
        }

        if ($description === '') {
            $this->addError('description', 'Description is required.');
        }

        if ($amount === '') {
            $this->addError('amount', 'Amount is required.');
        } elseif (!is_numeric($amount)) {
            $this->addError('amount', 'Amount must be a number.');
        }
    }

    public function handleTempFile(): bool
    {

        $tempFileName = tempnam(dirname($fileName), 'transactions_');
        if ($tempFileName === false) {
            fclose($file);
            return false;
        }

        $tempFile = fopen($tempFileName, 'w');
        if ($tempFile === false) {
            fclose($file);
            unlink($tempFileName);
            return false;
        }

        $this->tempFileName = $tempFileName;
        $this->tempFile = $tempFile;

        return true;

    }

    public function closeTempFile($action = false, $written = true): bool
    {
        if ($this->tempFile === null || $this->tempFileName === null) {
            return false;
        }
        fclose($tempFile);

        if (! $action || ! $written) {
            unlink($this->tempFileName);
            return false;
        }

        if (! rename(
            $this->tempFileName,
            $this->getFileName()
        )) {
            unlink($this->tempFileName);
            return false;
        }

        return true;

    }
}
