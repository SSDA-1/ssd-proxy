<?php

namespace ssda1\proxies\Http\Controllers;

use ssda1\proxies\Models\ProcessLog;

use Illuminate\Http\Request;

class ProcessLogController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:logs-list|logs-create|logs-edit|logs-delete', ['only' => ['index']]);
    }

    public function index()
    {
        $data = ProcessLog::orderBy('created_at', 'desc')->paginate(50);

        return view('proxies::admin.process-logs.index', compact('data'));
    }
}
