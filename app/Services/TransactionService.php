<?php

namespace App\Services;

use App\Interfaces\ITransactionService;
use App\Models\AccountTransaction;
use App\Models\Invoice;
use App\Models\Purchase;

class TransactionService implements ITransactionService
{
    protected $transactions;

    public function __construct(AccountTransaction $transactions)
    {
        $this->transactions = $transactions;
    }


    public function createTransaction(array $transaction) : AccountTransaction
    {
        return $this->transactions->create($transaction);
    }


    public function createTransactionFromExpense($request, $userId) : AccountTransaction
    {
        $reason = '['.config('config.expSubCatPrefix').'-'.$request->subCategory['code'].'] Expense payment';

        $transactionArray = [];
        $transactionArray['account_id'] = $request->account['id'];
        $transactionArray['amount'] = $request->amount;
        $transactionArray['reason'] = $reason;
        $transactionArray['type'] = 0;
        $transactionArray['transaction_date'] = $request->date;
        $transactionArray['cheque_no'] = $request->chequeNo;
        $transactionArray['receipt_no'] = $request->voucherNo;
        $transactionArray['created_by'] = $userId;
        $transactionArray['status'] = $request->status;

        return $this->createTransaction($transactionArray);

    }


    public function createTransactionFromInvoice($request, $userId, $invoice) : AccountTransaction
    {
        $reason = '['.config('config.invoicePrefix').'-'.$invoice->invoice_no.'] Invoice Payment added to ['.$request->account['accountNumber'].']';

        $transactionArray = [];
        $transactionArray['account_id'] = $request->account['id'];
        $transactionArray['amount'] = $request->paidAmount;
        $transactionArray['reason'] = $reason;
        $transactionArray['type'] = 1;
        $transactionArray['transaction_date'] = $request->date;
        $transactionArray['cheque_no'] = $request->chequeNo;
        $transactionArray['receipt_no'] = $request->receiptNo;
        $transactionArray['created_by'] = $userId;
        $transactionArray['status'] = $request->status;

        return $this->createTransaction($transactionArray);

    }

    public function createTransactionFromInvoicePayment($request, $userId, $selectedInvoice) : AccountTransaction
    {
        $invoice = Invoice::where('slug', $selectedInvoice['slug'])->first();

        $reason = '['.config('config.invoicePrefix').'-'.$invoice->invoice_no.'] Invoice Payment added to ['.$request->account['accountNumber'].']';

        $transactionArray = [];
        $transactionArray['account_id'] = $request->account['id'];
        $transactionArray['amount'] = $selectedInvoice['paidAmount'];
        $transactionArray['reason'] = $reason;
        $transactionArray['type'] = 1;
        $transactionArray['transaction_date'] = $request->paymentDate;
        $transactionArray['cheque_no'] = $request->chequeNo;
        $transactionArray['receipt_no'] = $request->receiptNo;
        $transactionArray['created_by'] = $userId;
        $transactionArray['status'] = $request->status;

        return $this->createTransaction($transactionArray);

    }


    public function createTransactionFromNonInvoicePayment($request, $userId) : AccountTransaction
    {

        $reason = '['.config('config.clientPrefix').'-'.$request->client['id'].'] Non inovice payment added to ['.$request->account['accountNumber'].']';

        $transactionArray = [];

        $transactionArray['account_id'] = $request->account['id'];
        $transactionArray['amount'] = $request->amount;
        $transactionArray['reason'] = $reason;
        $transactionArray['type'] = 1;
        $transactionArray['transaction_date'] = $request->paymentDate;
        $transactionArray['cheque_no'] = $request->chequeNo;
        $transactionArray['receipt_no'] = $request->receiptNo;
        $transactionArray['created_by'] = $userId;
        $transactionArray['status'] = $request->status;

        return $this->createTransaction($transactionArray);
    }


    public function createTransactionFromInvoiceReturn($request, $userId, $code) : AccountTransaction
    {

        $reason = '['.config('config.invoiceReturnPrefix').'-'.$code.'] Invoice Return payable sent from ['.$request->account['accountNumber'].']';

        $transactionArray = [];
        $transactionArray['account_id'] = $request->account['id'];
        $transactionArray['amount'] = $request->returnAmount;
        $transactionArray['reason'] = $reason;
        $transactionArray['type'] = 0;
        $transactionArray['transaction_date'] = $request->date;
        $transactionArray['cheque_no'] = $request->chequeNo;
        $transactionArray['receipt_no'] = $request->receiptNo;
        $transactionArray['created_by'] = $userId;
        $transactionArray['status'] = $request->status;

        return $this->createTransaction($transactionArray);

    }

    public function createTransactionFromLoan($request, $userId) : AccountTransaction
    {

        $reason = '['.$request->referenceNo.'] Loan added to ['.$request->account['accountNumber'].']';

        $transactionArray = [];

        $transactionArray['account_id'] = $request->account['id'];
        $transactionArray['amount'] = $request->amount;
        $transactionArray['reason'] = $reason;
        $transactionArray['type'] = 1;
        $transactionArray['transaction_date'] = $request->date;
        $transactionArray['created_by'] = $userId;
        $transactionArray['status'] = $request->status;

        return $this->createTransaction($transactionArray);
    }


    public function createTransactionFromLoanPayment($request, $userId) : AccountTransaction
    {

        $reason = '['.$request->loan['reference'].'] Loan Payment sent from ['.$request->account['accountNumber'].']';

        $transactionArray = [];

        $transactionArray['account_id'] = $request->account['id'];
        $transactionArray['amount'] = $request->amount;
        $transactionArray['reason'] = $reason;
        $transactionArray['type'] = 0;
        $transactionArray['transaction_date'] = $request->date;
        $transactionArray['created_by'] = $userId;
        $transactionArray['status'] = $request->status;

        return $this->createTransaction($transactionArray);
    }


    public function createTransactionFromNonPurchasePayment($request, $userId) : AccountTransaction
    {

        $reason = $reason = '['.config('config.supplierPrefix').'-'.$request->supplier['supplierID'].'] Non purchase due sent from ['.$request->account['accountNumber'].']';

        $transactionArray = [];

        $transactionArray['account_id'] = $request->account['id'];
        $transactionArray['amount'] = $request->amount;
        $transactionArray['reason'] = $reason;
        $transactionArray['type'] = 0;
        $transactionArray['transaction_date'] = $request->paymentDate;
        $transactionArray['cheque_no'] = $request->chequeNo;
        $transactionArray['receipt_no'] = $request->receiptNo;
        $transactionArray['created_by'] = $userId;
        $transactionArray['status'] = $request->status;

        return $this->createTransaction($transactionArray);
    }


    public function createTransactionFromPayroll($request, $userId) : AccountTransaction
    {

        $reason = '['.config('config.employeePrefix').'-'.$request->employee['empID'].'] '.$request->salaryMonth.' Payroll sent from ['.$request->account['accountNumber'].']';

        $transactionArray = [];

        $transactionArray['account_id'] = $request->account['id'];
        $transactionArray['amount'] = $request->totalSalary;
        $transactionArray['reason'] = $reason;
        $transactionArray['type'] = 0;
        $transactionArray['transaction_date'] = $request->salaryDate;
        $transactionArray['cheque_no'] = $request->chequeNo;
        $transactionArray['receipt_no'] = $request->receiptNo;
        $transactionArray['created_by'] = $userId;
        $transactionArray['status'] = $request->status;

        return $this->createTransaction($transactionArray);
    }


    public function createTransactionFromPayment($request, $userId, $purchase) : AccountTransaction
    {

        $reason = '['.config('config.purchasePrefix').'-'.$purchase->purchase_no.'] Purchase Payment sent from ['.$request->account['accountNumber'].']';

        $transactionArray = [];

        $transactionArray['account_id'] = $request->account['id'];
        $transactionArray['amount'] = $request->totalPaid;
        $transactionArray['reason'] = $reason;
        $transactionArray['type'] = 0;
        $transactionArray['transaction_date'] = $request->purchaseDate;
        $transactionArray['cheque_no'] = $request->chequeNo;
        $transactionArray['receipt_no'] = $request->receiptNo;
        $transactionArray['created_by'] = $userId;
        $transactionArray['status'] = $request->status;

        return $this->createTransaction($transactionArray);
    }


    public function createTransactionFromPurchasePayment($request, $userId, $selectedPurchase) : AccountTransaction
    {
        $purchase = Purchase::where('slug', $selectedPurchase['slug'])->first();

        $reason = '['.config('config.purchasePrefix').'-'.$purchase->purchase_no.'] Purchase Payment sent from ['.$request->account['accountNumber'].']';

        $transactionArray = [];

        $transactionArray['account_id'] = $request->account['id'];
        $transactionArray['amount'] = $selectedPurchase['paidAmount'];
        $transactionArray['reason'] = $reason;
        $transactionArray['type'] = 0;
        $transactionArray['transaction_date'] = $request->paymentDate;
        $transactionArray['cheque_no'] = $request->chequeNo;
        $transactionArray['receipt_no'] = $request->receiptNo;
        $transactionArray['created_by'] = $userId;
        $transactionArray['status'] = $request->status;

        return $this->createTransaction($transactionArray);
    }


    public function createTransactionFromPurchaseReturn($request, $userId, $code) : AccountTransaction
    {

        $reason = '['.config('config.purchaseReturnPrefix').'-'.$code.'] Purchase Return receivable added to ['.$request->account['accountNumber'].']';

        $transactionArray = [];

        $transactionArray['account_id'] = $request->account['id'];
        $transactionArray['amount'] = $request->returnAmount;
        $transactionArray['reason'] = $reason;
        $transactionArray['type'] = 1;
        $transactionArray['transaction_date'] = $request->date;
        $transactionArray['cheque_no'] = $request->chequeNo;
        $transactionArray['receipt_no'] = $request->receiptNo;
        $transactionArray['created_by'] = $userId;
        $transactionArray['status'] = $request->status;

        return $this->createTransaction($transactionArray);
    }



    public function createTransactionFromBalanceTransfer($request, $userId, int $type) : AccountTransaction
    {
        $reason = null;
        $accountId = null;

        if($type == 1)
        {
            $toAccountNumber = $request->toAccount['accountNumber'];
            $reason = "Balance transfer to [$toAccountNumber]";
            $accountId = $request->toAccount['id'];
        }
        else
        {
            $fromAccountNumber = $request->fromAccount['accountNumber'];
            $reason = "Balance transfer from [$fromAccountNumber]";
            $accountId = $request->fromAccount['id'];
        }

        $transactionArray = [];

        $transactionArray['account_id'] = $accountId;
        $transactionArray['amount'] = $request->amount;
        $transactionArray['reason'] = $reason;
        $transactionArray['type'] = $type;
        $transactionArray['transaction_date'] = $request->date;
        $transactionArray['created_by'] = $userId;
        $transactionArray['status'] = $request->status;

        return $this->createTransaction($transactionArray);
    }




}
