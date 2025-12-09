<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        $auditLogs = AuditLog::with('user')->orderByDesc('created_at')->paginate(10);
        return response()->json($auditLogs);
    }

    public function show(AuditLog $auditLog)
    {
        return response()->json($auditLog->load('user'));
    }
}