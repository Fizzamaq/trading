<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Services\AccountingService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
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
        $expenses = Expense::with(['category', 'creator'])
            ->orderBy('expense_date', 'desc')
            ->paginate(15);
        
        return view('director.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $categories = ExpenseCategory::all();
        
        return view('director.expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'category_id' => 'required|exists:expense_categories,id',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $category = ExpenseCategory::find($validated['category_id']);
            
            $expense = Expense::create([
                'expense_title' => $validated['expense_title'],
                'amount' => $validated['amount'],
                'expense_date' => $validated['expense_date'],
                'category_id' => $validated['category_id'],
                'account_id' => $category->account_id,
                'description' => $validated['description'],
                'created_by' => auth()->id(),
            ]);

            // Create accounting transaction
            $this->accountingService->createExpenseTransaction($expense);
            $this->activityLogService->logModelCreated($expense);

            DB::commit();

            return redirect()->route('director.expenses.index')
                ->with('success', 'Expense recorded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to record expense: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show(Expense $expense)
    {
        return view('director.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::all();
        return view('director.expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'expense_title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'category_id' => 'required|exists:expense_categories,id',
            'description' => 'nullable|string',
        ]);
        
        $originalExpense = $expense->getOriginal();
        
        DB::beginTransaction();
        try {
            $category = ExpenseCategory::find($validated['category_id']);
            $expense->update([
                'expense_title' => $validated['expense_title'],
                'amount' => $validated['amount'],
                'expense_date' => $validated['expense_date'],
                'category_id' => $validated['category_id'],
                'account_id' => $category->account_id,
                'description' => $validated['description'],
            ]);
            
            $this->activityLogService->logModelUpdated($expense, $originalExpense);
            
            DB::commit();

            return redirect()->route('director.expenses.index')->with('success', 'Expense updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update expense: ' . $e->getMessage()])
                ->withInput();
        }
    }
}