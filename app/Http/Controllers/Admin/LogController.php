<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aktivitas;

class LogController extends Controller
{
    public function index()
    {
        $logs = Aktivitas::latest()->paginate(20);
        return view('admin.pages.logs.index', compact('logs'));
    }
}
