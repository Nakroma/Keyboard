<?php

namespace Keyboard\Http\Controllers;

use Illuminate\Http\Request;
use Keyboard\Thread;
use Keyboard\User;
use Keyboard\Visit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Keyboard\Providers\CallnameService;

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
        $pinned = Thread::orderBy('last_post', 'desc')->where('pinned', true)->get();
        $threads = Thread::orderBy('last_post', 'desc')
            ->where('pinned', false)
            ->paginate(config('_custom.threadPagination'));

        // Check if new posts are in the thread
        $threads = $this->new_posts($threads);
        $pinned = $this->new_posts($pinned);

        return view('board', [
            'threads' => $threads,
            'pinned' => $pinned,
        ]);
    }

    /**
     * Marks threads with new posts
     *
     * @param $threads
     * @return mixed
     */
    private function new_posts($threads)
    {
        foreach ($threads as $th) {
            $visits = Visit::where([
                ['author', '=', Auth::id()],
                ['thread', '=', $th->id],
            ])->get();

            $th->new_posts = true;
            if (count($visits) > 0) {
                if ($visits[0]->updated_at >= $th->last_post) {
                    $th->new_posts = false;
                }
            }
        }
        return $threads;
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
        $thread->last_post = date("Y-m-d H:i:s");
        if (Auth::user()->group >= config('_custom.permissions')['pinnedThread']) {
            if ($request->pinned == 'on') {
                $thread->pinned = true;
            }
        }
        $thread->save();

        // Create callname
        CallnameService::assignCallname($thread->id, $thread->pinned);

        return redirect('thread/'.$thread->id);
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
