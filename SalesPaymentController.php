<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\SalesInvoice;
use App\Models\SalesPayment;
use App\Models\Customer;
use App\Services\AccountingService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesPaymentController extends Controller
{
    protected $accountingService;
    protected $activityLogService;

    public function __construct(AccountingService $accountingService, ActivityLogService $activityLogService)
    {
        $this->middleware(['auth', 'role:director,owner']);
        $this->accountingService = $accountingService;
        $this->activityLogService = $activityLogService;
    }

    public function index()
    {
        $payments = SalesPayment::with(['customer', 'creator'])
            ->orderBy('payment_date', 'desc')
            ->paginate(15);
        return view('director.payments.index', compact('payments'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        return view('director.payments.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_date' => 'required|date',
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:255',
            'sales_invoice_id' => 'required|exists:sales_invoices,id',
        ]);

        DB::beginTransaction();

        try {
            $salesInvoice = SalesInvoice::findOrFail($request->sales_invoice_id);

            if ($salesInvoice->status == 'paid') {
                throw new \Exception('Invoice is already fully paid.');
            }

            if ($request->payment_amount > $salesInvoice->remaining_amount) {
                throw new \Exception('Payment amount exceeds the remaining invoice balance.');
            }

            // Create the new payment record
            $payment = SalesPayment::create([
                'payment_reference' => 'PAY-' . now()->format('Ymd') . '-' . (SalesPayment::count() + 1),
                'customer_id' => $request->customer_id,
                'payment_date' => $request->payment_date,
                'payment_amount' => $request->payment_amount,
                'payment_method' => $request->payment_method,
                'created_by' => auth()->id(),
            ]);

            // Update the sales invoice with the payment
            $salesInvoice->paid_amount += $payment->payment_amount;
            $salesInvoice->remaining_amount -= $payment->payment_amount;

            // Update status based on remaining amount
            if ($salesInvoice->remaining_amount <= 0) {
                $salesInvoice->status = 'paid';
                $salesInvoice->settled_date = $payment->payment_date;
            } elseif ($salesInvoice->paid_amount > 0) {
                $salesInvoice->status = 'partially_paid';
            }

            $salesInvoice->save();

            // Create accounting transaction
            $this->accountingService->createSalesPaymentTransaction($payment, $salesInvoice);
            $this->activityLogService->logModelCreated($payment);

            DB::commit();

            return redirect()->route('director.sales-payments.index')
                ->with('success', 'Payment recorded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to record payment: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show(SalesPayment $payment)
    {
        $payment->load('customer');
        return view('director.payments.show', compact('payment'));
    }

    public function edit(SalesPayment $payment)
    {
        $customers = Customer::all();
        $payment->load('customer');
        return view('director.payments.edit', compact('payment', 'customers'));
    }

    public function update(Request $request, SalesPayment $payment)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_date' => 'required|date',
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:255',
        ]);
        
        $original = $payment->getOriginal();
        $payment->update($validated);
        $this->activityLogService->logModelUpdated($payment, $original);
        
        return redirect()->route('director.payments.index')
            ->with('success', 'Payment updated successfully!');
    }
}
