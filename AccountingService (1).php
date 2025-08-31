<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use App\Models\SalesPayment;
use App\Models\Transaction;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    /**
     * Creates a transaction for a new Purchase Invoice.
     * Debits: Inventory
     * Credits: Accounts Payable
     */
    public function createPurchaseInvoiceTransaction(PurchaseInvoice $invoice)
    {
        $inventoryAccount = ChartOfAccount::firstWhere('account_code', '1300');
        $accountsPayableAccount = ChartOfAccount::firstWhere('account_code', '2100');

        if (!$inventoryAccount || !$accountsPayableAccount) {
            throw new \Exception('Required Chart of Accounts not found for Purchase Invoice transaction.');
        }

        Transaction::create([
            'transaction_date' => $invoice->invoice_date,
            'reference' => $invoice->invoice_number,
            'description' => 'Purchase Invoice from ' . ($invoice->supplier->name ?? 'N/A'),
            'debit_account_id' => $inventoryAccount->id,
            'credit_account_id' => $accountsPayableAccount->id,
            'amount' => $invoice->total_amount,
            'source_type' => get_class($invoice),
            'source_id' => $invoice->id,
            'created_by' => Auth::id(),
        ]);
    }

    /**
     * Creates a transaction for a new Sales Invoice.
     * Debits: Accounts Receivable
     * Credits: Sales Revenue
     */
    public function createSalesInvoiceTransaction(SalesInvoice $invoice)
    {
        $accountsReceivableAccount = ChartOfAccount::firstWhere('account_code', '1200');
        $salesRevenueAccount = ChartOfAccount::firstWhere('account_code', '4100');
        $costOfGoodsSoldAccount = ChartOfAccount::firstWhere('account_code', '5100');
        $inventoryAccount = ChartOfAccount::firstWhere('account_code', '1300');

        if (!$accountsReceivableAccount || !$salesRevenueAccount || !$costOfGoodsSoldAccount || !$inventoryAccount) {
            throw new \Exception('Required Chart of Accounts not found for Sales Invoice transaction.');
        }

        DB::transaction(function () use ($invoice, $accountsReceivableAccount, $salesRevenueAccount, $costOfGoodsSoldAccount, $inventoryAccount) {
            // Transaction to record revenue
            Transaction::create([
                'transaction_date' => $invoice->invoice_date,
                'reference' => $invoice->invoice_number,
                'description' => 'Sales Invoice to ' . ($invoice->customer->name ?? 'N/A'),
                'debit_account_id' => $accountsReceivableAccount->id,
                'credit_account_id' => $salesRevenueAccount->id,
                'amount' => $invoice->total_amount,
                'source_type' => get_class($invoice),
                'source_id' => $invoice->id,
                'created_by' => Auth::id(),
            ]);

            // Transaction to record cost of goods sold
            Transaction::create([
                'transaction_date' => $invoice->invoice_date,
                'reference' => $invoice->invoice_number . ' (COGS)',
                'description' => 'Cost of goods sold for ' . ($invoice->customer->name ?? 'N/A'),
                'debit_account_id' => $costOfGoodsSoldAccount->id,
                'credit_account_id' => $inventoryAccount->id,
                'amount' => $invoice->cost_of_goods,
                'source_type' => get_class($invoice),
                'source_id' => $invoice->id,
                'created_by' => Auth::id(),
            ]);
        });
    }

    /**
     * Creates a transaction for a Sales Payment received.
     * Debits: Cash
     * Credits: Accounts Receivable
     */
    public function createSalesPaymentTransaction(SalesPayment $payment, SalesInvoice $invoice)
    {
        $cashAccount = ChartOfAccount::firstWhere('account_code', '1110');
        $accountsReceivableAccount = ChartOfAccount::firstWhere('account_code', '1200');

        if (!$cashAccount || !$accountsReceivableAccount) {
            throw new \Exception('Required Chart of Accounts not found for Sales Payment transaction.');
        }

        Transaction::create([
            'transaction_date' => $payment->payment_date,
            'reference' => $payment->payment_reference,
            'description' => 'Payment received from ' . ($payment->customer->name ?? 'N/A') . ' for invoice ' . $invoice->invoice_number,
            'debit_account_id' => $cashAccount->id,
            'credit_account_id' => $accountsReceivableAccount->id,
            'amount' => $payment->payment_amount,
            'source_type' => get_class($payment),
            'source_id' => $payment->id,
            'created_by' => Auth::id(),
        ]);
    }

    /**
     * Creates a transaction for a new Expense.
     * Debits: Specific Expense Account (from Expense Category)
     * Credits: Cash
     */
    public function createExpenseTransaction(Expense $expense)
    {
        $expenseAccount = $expense->category->account;
        $cashAccount = ChartOfAccount::firstWhere('account_code', '1110');

        if (!$expenseAccount || !$cashAccount) {
            throw new \Exception('Required Chart of Accounts not found for Expense transaction.');
        }

        Transaction::create([
            'transaction_date' => $expense->expense_date,
            'reference' => 'EXP-' . $expense->id,
            'description' => $expense->expense_title,
            'debit_account_id' => $expenseAccount->id,
            'credit_account_id' => $cashAccount->id,
            'amount' => $expense->amount,
            'source_type' => get_class($expense),
            'source_id' => $expense->id,
            'created_by' => Auth::id(),
        ]);
    }
}