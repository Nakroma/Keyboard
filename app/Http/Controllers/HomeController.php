<?php

namespace laravelTest\Http\Controllers;

use Illuminate\Http\Request;
use laravelTest\Thread;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Pass threads to view
        $threads = Thread::orderBy('created_at', 'asc')->get();

        return view('board', [
            'threads' => $threads
        ]);
    }
}
