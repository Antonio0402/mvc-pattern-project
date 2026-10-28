<?php

namespace App\Controllers;

use App\Models\Transaction as TransactionModel;
use App\Model\Database;
use App\Framework\Controller;

class Transactions extends Controller
{
    private static ?TransactionModel $transactionModel = null;
    private $data = null;


    public function __construct(
    ) {
        if (self::$transactionModel === null) {
            self::$transactionModel = new TransactionModel(new Database(TransactionModel::$filename));
        }

        $this->data = self::$transactionModel->getTransactionData();
    }

    public function index()
    {
        // require_once APP_PATH . 'models/transaction.php';
        $transactions = $this->data['transactions'];
        $total = $this->data['total'];

        extract($transactions, EXTR_SKIP);
        extract($total, EXTR_SKIP);

        echo $this->view->render('Transaction/show.mvc.php', [
            'transactions' => $transactions,
            'total' => $total
        ]);
    }
    public function show(string $slug)
    {
        $currentTransaction = $this->getTransaction($slug);

        echo $this->view->render('Transaction/slug.mvc.php', [
            'currentTransaction' => $currentTransaction
            ]);
    }

    public function showPage(string $title, int $id, string $page)
    {
        echo 'This page include data from ' . "{$title}, {$id}, and {$page}";
    }

    public function update()
    {
        $transaction = self::$transactionModel->updateTransaction($row);

        $itemSlug = self::$transactionModel->getNameOfTransaction($transaction['checkNumber']);
        if ($transaction) {
            header("Location: /transactions/{$itemSlug}");
            exit();
        } else {
            $errors = self::$transactionModel->getErrors();

            echo $this->view->render('Transaction/edit.mvc.php', [
                'transaction' => $transaction,
                'errors' => $errors,
                'btnText' => 'Save',
                'slug' => $itemSlug
            ]);
        }
    }

    public function insert()
    {
        $row = $this->request->post;

        $transaction = self::$transactionModel->insertTransaction($row);

        if ($transaction) {
            $itemSlug = self::$transactionModel->getNameOfTransaction($transaction['checkNumber']);
            header("Location: /transactions/{$itemSlug}");
            exit();
        } else {
            $errors = self::$transactionModel->getErrors();

            echo $this->view->render('Transaction/new.mvc.php', [
                'transaction' => $transaction,
                'errors' => $errors,
                'btnText' => 'Create'
            ]);
        }

    }
    public function edit(string $id)
    {
        $transaction = $this->getTransaction($id, 'checkNumber');

        $itemSlug = self::$transactionModel->getNameOfTransaction($transaction['checkNumber']);

        echo $this->view->render('Transaction/edit.mvc.php', [
            'transaction' => $transaction,
            'btnText' => 'Save',
            'slug' => $itemSlug
        ]);
    }

    public function delete(string $id)
    {

        $transaction = $this->getTransaction($id, 'checkNumber');

        $itemSlug = self::$transactionModel->getNameOfTransaction($transaction['checkNumber']);

        echo $this->view->render('Transaction/delete.mvc.php', [
            'id' => $id,
            'slug' => $itemSlug
        ]);

    }

    public function destroy(string $id)
    {

        $row = $this->getTransaction($id, 'checkNumber');

        $deletedTransaction = self::$transactionModel->deleteTransaction($row);
        // if (!$deletedTransaction) {
        //     throw new PageNotFoundException("404 Not Found on route of {$id}");
        //     exit();
        // }
        header("Location: /transactions/show");
        exit();
    }

    private function getTransaction(string $id, $type = 'slug')
    {

        $transaction = [];
        if ($type = 'slug') {
            if (empty($this->$data) && ! empty($id)) {
                foreach ($data['transactions'] as $item) {
                    $itemCheckID = getIdByType($type);
                    if ($itemCheckID === $id) {
                        $transaction = $item;
                        break;
                    }
                }
            }
        }

        if (empty($transaction)) {
            throw new PageNotFoundException("404 Not Found on route of {$id}");
        }

    }

    private function getIdByType($type)
    {
        return match($type) {
            "slug" => strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $item['checkNumber']), '-')),
            default => $item['checkNumber']
        };
    }
}
