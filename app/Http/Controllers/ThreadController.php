<?php

namespace laravelTest\Http\Controllers;

use Illuminate\Http\Request;
use laravelTest\Thread;
use laravelTest\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Creates a new post.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPost(Request $request)
    {
        // Validating
        $validator = Validator::make($request->all(), [
            'body' => 'required|max:1000',
            'thread' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator);
        }

        // Creating post
        $post = new Post;
        $post->body = $request->body;
        $post->thread = $request->thread;
        $post->author = Auth::id();
        $post->save();

        return back();
    }
}
