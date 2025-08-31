<?php

namespace App\Http\Controllers\Owner;

use App\Models\ProfitDistribution;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Investor;
use App\Models\InvestorRequest;
use App\Services\NotificationService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InvestorManagementController extends Controller
{
    protected NotificationService $notificationService;
    protected ActivityLogService $activityLogService;

    public function __construct(NotificationService $notificationService, ActivityLogService $activityLogService)
    {
        $this->middleware(['auth', 'owner']);
        $this->notificationService = $notificationService;
        $this->activityLogService = $activityLogService;
    }

    // List investors
    public function index()
    {
        $investors = Investor::with('user')->paginate(15);
        return view('owner.investors.index', compact('investors'));
    }

    // Show investor creation form
    public function create()
    {
        return view('owner.investors.create');
    }

    // Store new investor
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'investment_amount' => 'required|numeric|min:1000',
            'profit_percentage' => 'required|numeric|min:0|max:100',
            'grace_period_start' => 'required|date',
        ]);

        $totalShares = intval($validated['investment_amount'] / 1000);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('password123'),
            'role' => 'investor',
            'status' => 'pending_approval',
            'phone' => $validated['phone'] ?? null,
        ]);

        $investor = Investor::create([
            'user_id' => $user->id,
            'investment_amount' => $validated['investment_amount'],
            'total_shares' => $totalShares,
            'profit_percentage' => $validated['profit_percentage'],
            'grace_period_start' => $validated['grace_period_start'],
        ]);

        $this->activityLogService->logModelCreated($investor);

        return redirect()->route('owner.investors.index')->with('success', 'Investor created successfully!');
    }

    // Show investor details
    public function show(Investor $investor)
    {
        $investor->load(['user', 'profitDistributions.monthlyProfit', 'requests']);
        return view('owner.investors.show', compact('investor'));
    }

    // Approve investor user
    public function approve(User $user)
    {
        $user->update(['status' => 'active']);

        $this->activityLogService->logActivity([
            'action' => 'investor_approved',
            'model_type' => get_class($user),
            'model_id' => $user->id,
        ]);

        return redirect()->back()->with('success', 'Investor approved successfully!');
    }

    // Investor Requests listing
    public function investorRequests()
    {
        $investorRequests = InvestorRequest::with(['investor.user'])
            ->orderBy('requested_at', 'desc')
            ->paginate(15);

        return view('owner.investor-requests', compact('investorRequests'));
    }

    // Approve investor request
    public function approveRequest(InvestorRequest $request)
    {
        $original = $request->replicate();

        $request->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => 'Approved by owner',
        ]);

        $this->activityLogService->logModelUpdated($request, $original->getAttributes());

        return redirect()->back()->with('success', 'Request approved successfully!');
    }

    // Reject investor request
    public function rejectRequest(InvestorRequest $request, Request $httpRequest)
    {
        $httpRequest->validate([
            'review_notes' => 'required|string|max:1000',
        ]);

        $original = $request->replicate();

        $request->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $httpRequest->review_notes,
        ]);

        $this->activityLogService->logModelUpdated($request, $original->getAttributes());

        return redirect()->back()->with('success', 'Request rejected.');
    }
}
