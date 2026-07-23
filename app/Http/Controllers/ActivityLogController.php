<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = ActivityLog::with('user')
            ->when($request->filled('user_id'), fn ($q) => $q->where('user_id', $request->integer('user_id')))
            ->when($request->filled('table_name'), fn ($q) => $q->where('table_name', $request->string('table_name')))
            ->when($request->filled('date'), fn ($q) => $q->whereDate('created_at', $request->date('date')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $users = User::orderBy('name')->get();

        return view('activity-logs.index', compact('logs', 'users'));
    }
}
