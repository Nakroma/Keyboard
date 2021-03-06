<?php

namespace Keyboard\Http\Controllers;

use Illuminate\Http\Request;
use Keyboard\Thread;
use Keyboard\Post;
use Keyboard\Callname;
use Keyboard\Visit;
use Keyboard\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Keyboard\Providers\CallnameService;
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

            //$p->username = User::where('id', $p->author)->first()->username;
        }
        //$thread->username = User::where('id', $thread->author)->first()->username;

        // Create callname array
        $raw_callnames = Callname::where('thread', $id)->get();
        $string_callnames = config('_custom.callnames');
        $callnames = [];
        $mod_reveal = [];
        foreach($raw_callnames as $raw) {
            // Set callname or custom id
            if ($raw->callname >= 0) {
                $callnames[$raw->author] = $string_callnames[$raw->callname];
            } else {
                $callnames[$raw->author] = $raw->custom_id;
            }

            // Set mod reveal
            $mod_reveal[$raw->author] = $raw->moderator;
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
            'moderator' => $mod_reveal,
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

    /**
     * Reveals a user as a mod
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revealModStatus($id)
    {
        if (Auth::user()->group >= config('_custom.permissions')['revealModStatus']) {
            Callname::where([
                ['thread', '=', $id],
                ['author', '=', Auth::id()],
            ])->update(['moderator' => true]);
        }

        return redirect('thread/'.$id);
    }
}
