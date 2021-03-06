<?php

namespace Keyboard\Http\Controllers\Auth;

use Keyboard\User;
use Keyboard\Key;
use Keyboard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Keyboard\Providers\KeyGenerationService;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/board';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'username' => 'required|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'key' => 'required|min:5|serial',
        ]);

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        // Generate key
        $key = KeyGenerationService::generateKey();

        // Set old key to used
        Key::where('key_value', $data['key'])->update(['used' => true]);

        // Check for admin key, if yes set to highest group
        $group = 0;
        if ($data['key'] == 'ADMIN_KEY') {
            $group = max(array_keys(config('_custom.groups')));
        }

        return User::create([
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
            'group' => $group,
            'key_id' => $key->id
        ]);
    }
}
