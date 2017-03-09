<?php

namespace laravelTest\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use laravelTest\Key;
use laravelTest\Callname;
use laravelTest\User;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Bans a user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function banUser(Request $request)
    {
        // Validating
        $validator = Validator::make($request->all(), [
            'thread' => 'required|integer',
            'callname' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator);
        }

        $callnames = config('_custom.callnames');
        $key = array_search($request->callname, $callnames);

        if ($key && Auth::user()->group >= config('_custom.permissions')['banUser']) {
            $callname = Callname::where([
                ['thread', '=', $request->thread],
                ['callname', '=', $key],
            ])->first();
            User::where('id', $callname->author)->update(['group' => min(array_keys(config('_custom.groups')))]);
        }

        return back();
    }
}
