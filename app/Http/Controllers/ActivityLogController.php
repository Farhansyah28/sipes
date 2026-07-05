<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('causer')->latest()->paginate(20);
        return view('activity_log.index', compact('logs'));
    }
}
