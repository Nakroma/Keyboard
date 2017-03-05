<?php

namespace laravelTest\Http\Controllers;

use Illuminate\Http\Request;
use laravelTest\Thread;
use laravelTest\Post;
use laravelTest\Callname;
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
        $posts = Post::where('thread', $id)->get();

        return view('view_thread', [
            'thread' => $thread,
            'posts' => $posts
        ]);
    }

    public function assignCallname($thread_id)
    {
        $callname = uniqid();

        $callnames = config('_custom.callnames'); // Available Callnames
        $used_callnames = Callname::where('thread', $thread_id)->get();

        // Iterate through used callnames and remove them from available ones
        foreach ($used_callnames as $cn) {
            if ($cn->callname >= 0)
                unset($callnames[$cn->callname]);
        }

        // Assign ID if no callnames left, otherwise assign callname
        if (count($callnames) > 0)
            $callname = array_rand($callnames);

        return $callname;
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
