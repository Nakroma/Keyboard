<?php

namespace laravelTest\Http\Controllers;

use Illuminate\Http\Request;
use laravelTest\Thread;
use laravelTest\Post;
use laravelTest\Callname;
use laravelTest\Visit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use laravelTest\Providers\CallnameService;
use Golonka\BBCode\Facades\BBCodeParser;

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
        $posts = Post::where('thread', $id)->paginate(config('_custom.postPagination'));

        // Escape and print BB Code
        $thread->body = htmlspecialchars($thread->body);
        $thread->body = BBCodeParser::parse($thread->body);
        // Do the same for all posts
        foreach($posts as $p) {
            $p->body = htmlspecialchars($p->body);
            $p->body = BBCodeParser::parse($p->body);
        }

        // Create callname array
        $raw_callnames = Callname::where('thread', $id)->get();
        $string_callnames = config('_custom.callnames');
        $callnames = [];
        foreach($raw_callnames as $raw) {
            $callnames[$raw->author] = $string_callnames[$raw->callname];
        }

        // Create or update visit
        $visit = Visit::where([
            ['thread', '=', $id],
            ['author', '=', Auth::id()],
        ])->get();
        if (count($visit) > 0) {
            Visit::where([
                ['thread', '=', $id],
                ['author', '=', Auth::id()],
            ])->update(['updated_at' => date("Y-m-d H:i:s")]);
        } else {
            $v = new Visit;
            $v->thread = $id;
            $v->author = Auth::id();
            $v->save();
        }

        return view('view_thread', [
            'thread' => $thread,
            'posts' => $posts,
            'callnames' => $callnames,
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

        // Update thread last_post time
        Thread::where('id', $request->thread)->update(['last_post' => date("Y-m-d H:i:s")]);

        // Create callname
        CallnameService::assignCallname($request->thread);

        return back();
    }

    /**
     * Deletes a post.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePost($id)
    {
        $post = Post::where('id', $id)->first();
        $permissions = config('_custom.permissions');

        if (Auth::user()->group >= $permissions['deletePost']) {
            $post->delete();
            return redirect('thread/'.$post->thread);
        } else {
            return redirect('board');
        }
    }
}
