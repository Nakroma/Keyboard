<?php

namespace laravelTest\Http\Controllers;

use Illuminate\Http\Request;
use laravelTest\Thread;
use laravelTest\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use laravelTest\Providers\CallnameService;

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
        $threads = Thread::orderBy('updated_at', 'desc')->paginate(config('_custom.threadPagination'));

        return view('board', [
            'threads' => $threads
        ]);
    }

    /**
     * Show the thread creation form.
     *
     * @return \Illuminate\Http\Response
     */
    public function threadForm()
    {
        return view('thread');
    }

    /**
     * Create a new thread.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createThread(Request $request)
    {
        // Validating
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect('thread')
                ->withInput()
                ->withErrors($validator);
        }

        // Creating thread
        $thread = new Thread;
        $thread->title = $request->title;
        $thread->body = $request->body;
        $thread->author = Auth::id();
        $thread->save();

        // Create callname
        CallnameService::assignCallname($thread->id);

        return redirect('board');
    }

    /**
     * Deletes a thread.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteThread($id)
    {
        $thread = Thread::where('id', $id)->first();
        $requester = User::where('id', Auth::id())->first();
        $permissions = config('_custom.permissions');

        if (($requester->id == $thread->author) || ($requester->group >= $permissions['deleteThread'])) {
            $thread->delete();
            return redirect('board');
        } else {
            return redirect('thread/'.$id);
        }
    }
}
