<?php

namespace App\Models;

class Database
{
    private $files = [];
    private $file = null;
    public static $data_path = DATA_PATH;
    public function __contruct(
        string $fileName
    ) {
        $this->$files = $this->getDataFromDirectory();

        if (! file_exists($fileName) || !in_array($fileName, $this->$files)) {
            trigger_error('File not found: ' . $fileName, E_USER_ERROR);
        }
        $this->file = fopen($fileName, 'r');
    }


    public function getDataFromDirectory(string $dirPath = self::data_path): array
    {
        $files = [];
        $dir   = scandir($dirPath);
        foreach ($dir as $file) {
            if (is_dir($file)) {
                continue;
            }

            $files[] = $dirPath . $file;
        }

        return $files;
    }

    public function getDataFromFile(string $fileName, ?callable $transactionHandler = null): array
    {
        $file =  $this->file;
        $transactions = [];
        while (($transaction = fgetcsv($file)) !== false) {
            if ($transactionHandler) {
                $transaction = $transactionHandler($transaction);
            }
            $transactions[] = $transaction;
        }

        return $transactions;
    }


    public function getDataById(int $id = 0): array
    {
        if ($id < 1) {
            return [];
        }

        if (!$this->file) {
            return null;
        }

        fgetcsv($this->file);
        $currentId = 1;

        while (($transaction = fgetcsv($this->file)) !== false) {
            if ($currentId === $id) {
                return $transaction;
            }

            $currentId++;
        }

        return null;
    }

    public function allFilesInfo()
    {

        if (empty($this->files)) {
            $this->files = getDataFromDirectory(self::$data_path);
        }
        return $this->files;

    }


    public function __destruct()
    {
        if ($this->file) {
            fclose($this->file);
        }
    }

}
