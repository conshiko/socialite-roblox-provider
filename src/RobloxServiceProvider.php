<?php

namespace Conshiko\Laravel\Socialite;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class RobloxServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Socialite::extend('roblox', function (Application $app) {
            $config = $app->make('config')->get('services.roblox');
            $redirect = value(Arr::get($config, 'redirect'));

            return new RobloxProvider(
                $app->make('request'),
                $config['client_id'],
                $config['client_secret'],
                Str::startsWith($redirect, '/') ? $app->make('url')->to($redirect) : $redirect,
                Arr::get($config, 'guzzle', []),
            );
        });
    }
}
