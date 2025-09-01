<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'owner']);
    }

    public function index()
    {
        $transactions = Transaction::with(['debitAccount', 'creditAccount', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('owner.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['debitAccount', 'creditAccount', 'creator']);
        return view('owner.transactions.show', compact('transaction'));
    }

    public function export(Request $request)
    {
        $transactions = Transaction::with(['debitAccount', 'creditAccount', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="transactions_export_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, ['ID', 'Date', 'Reference', 'Description', 'Debit Account', 'Credit Account', 'Amount', 'Created By']);

            // CSV Data
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->transaction_date->format('Y-m-d'),
                    $transaction->reference,
                    $transaction->description,
                    $transaction->debitAccount->account_name,
                    $transaction->creditAccount->account_name,
                    $transaction->amount,
                    $transaction->creator->name ?? 'N/A',
                ]);
            }
            fclose($file);
        };
        
        return new StreamedResponse($callback, 200, $headers);
    }
}