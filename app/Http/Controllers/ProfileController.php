<?php

namespace laravelTest\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use laravelTest\Key;

class ProfileController extends Controller
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
     * Show the profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $key = Key::where('id', Auth::user()->key_id)->first();

        // Show moderation area depending on the permissions the user has
        $prm = config('_custom.permissions');
        $grp = Auth::user()->group;
        $moderationArea = ($grp >= $prm['banUser'] || $grp >= $prm['promoteUser'] || $grp >= $prm['createKey']);

        return view('profile', [
            'user' => Auth::user(),
            'key' => $key,
            'mod_area' => $moderationArea,
            'prm' => $prm,
        ]);
    }
}
