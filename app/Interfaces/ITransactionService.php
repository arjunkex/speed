<?php

namespace App\Interfaces;


interface ITransactionService
{
    public function createTransactionFromExpense($request, $userId);

    public function createTransactionFromInvoice($request, $userId, $invoice);

    public function createTransactionFromInvoicePayment($request, $userId, $selectedInvoice);

    public function createTransactionFromInvoiceReturn($request, $userId, $code);

    public function createTransactionFromLoan($request, $userId);

    public function createTransactionFromLoanPayment($request, $userId);

    public function createTransactionFromNonInvoicePayment($request, $userId);

    public function createTransactionFromNonPurchasePayment($request, $userId);

    public function createTransactionFromPayroll($request, $userId);

    public function createTransactionFromPayment($request, $userId, $purchase);

    public function createTransactionFromPurchasePayment($request, $userId, $selectedPurchase);

    public function createTransactionFromPurchaseReturn($request, $userId, $code);

    public function createTransactionFromBalanceTransfer($request, $userId, int $type);


}
