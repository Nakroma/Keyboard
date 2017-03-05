<?php

namespace laravelTest\Http\Controllers;

use Illuminate\Http\Request;
use laravelTest\Thread;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Create a new thread
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createThread(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|max:255',
            'content' => 'required|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect('/thread')
                ->withInput()
                ->withErrors($validator);
        }

        $thread = new Thread;
        $thread->title = $request->title;
        $thread->body = $request->body;
        $thread->author = Auth::id();
        $thread->save();


        return redirect('/board');
    }
}
