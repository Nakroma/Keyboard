<?php

namespace Keyboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Keyboard\Key;
use Keyboard\Callname;
use Keyboard\User;
use Illuminate\Support\Facades\Validator;
use Keyboard\Providers\KeyGenerationService;

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
            'groups' => config('_custom.groups'),
            'key_list' => [],
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

    /**
     * Promotes a user to either a group under you or the same one you have.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function promoteUser(Request $request)
    {
        // Validating
        $validator = Validator::make($request->all(), [
            'group' => 'required',
            'username' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator);
        }

        $groups = config('_custom.groups');
        $group = 0;
        if (is_numeric($request->group)) {
            $group = $request->group;
        } else {
            $group = array_search($request->group, $groups);
        }

        if ($group <= Auth::user()->group && Auth::user()->group >= config('_custom.permissions')['promoteUser']) {
            User::where('username', $request->username)->update(['group' => $group]);
        }

        return back();
    }

    /**
     * Generates a certain number of keys
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateKey(Request $request)
    {
        // Validating
        $validator = Validator::make($request->all(), [
            'number' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator);
        }

        if (Auth::user()->group >= config('_custom.permissions')['createKey']) {
            $keyArray = [];
            for ($i = 0; $i < $request->number; $i++) {
                $keyArray[] = KeyGenerationService::generateKey();
            }

            return view('profile', [
                'key_list' => $keyArray,
            ]);
        }

        return back();
    }
}
