<?php

namespace laravelTest\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use laravelTest\Key;

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
