<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogService;

class UserController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->middleware(['auth', 'owner']);
        $this->activityLogService = $activityLogService;
    }

    public function index()
    {
        $users = User::paginate(15);
        return view('owner.users.index', compact('users'));
    }

    public function create()
    {
        return view('owner.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:owner,director,investor',
            'status' => 'required|in:active,inactive,pending_approval',
            'phone' => 'nullable|string|max:20',
        ]);
        
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'status' => $validated['status'],
                'phone' => $validated['phone'],
            ]);

            $this->activityLogService->logModelCreated($user);
            DB::commit();

            return redirect()->route('owner.users.index')->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function show(User $user)
    {
        return view('owner.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('owner.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|in:owner,director,investor',
            'status' => 'required|in:active,inactive,pending_approval',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $original = $user->getOriginal();
        
        DB::beginTransaction();
        try {
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];
            $user->status = $validated['status'];
            $user->phone = $validated['phone'];
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();
            
            $this->activityLogService->logModelUpdated($user, $original);
            DB::commit();

            return redirect()->route('owner.users.index')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }
}