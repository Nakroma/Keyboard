<?php

namespace laravelTest\Http\Controllers;

use Illuminate\Http\Request;
use laravelTest\Thread;

class ThreadController extends Controller
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
     * Show the corresponding thread.
     *
     * @param Integer $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        // Pass thread and answers to view
        $thread = Thread::where('id', $id)->first();
        // $posts = Post::where('thread_id', $id)->get();

        return view('view_thread', [
            'thread' => $thread
        ]);
    }
}
