<?php

namespace laravelTest\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use laravelTest\Key;

class ProfileController extends Controller
{
    /**
     * Show the profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $key = Key::where('id', Auth::user()->key_id)->first();

        return view('profile', [
            'user' => Auth::user(),
            'key' => $key,
        ]);
    }
}
