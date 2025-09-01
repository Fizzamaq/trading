<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AuditLogsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'owner']);
    }

    public function index()
    {
        $auditLogs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('owner.audit-logs.index', compact('auditLogs'));
    }
}