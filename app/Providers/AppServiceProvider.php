<?php

namespace laravelTest\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use laravelTest\Key;
use laravelTest\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // Key validation
        Validator::extend('serial', function($attribute, $value, $parameters, $validator) {
            $getKey = Key::where('key_value', $value)->get();
            return ((count($getKey) > 0) && !$getKey[0]['used']);
        });

        // Banned?
        Validator::extend('banned', function($attribute, $value, $parameters, $validator) {
            $user = User::where('username', $value)->first();
            $bannedGroup = min(array_keys(config('_custom.groups')));
            return $user->group > $bannedGroup;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
