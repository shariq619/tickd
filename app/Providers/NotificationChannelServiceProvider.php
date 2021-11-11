<?php

namespace App\Providers;

use App\Core\Channels\FirebaseChannel;
use App\Core\Channels\ValueFirstChannel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;

class NotificationChannelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('value_first', function () {
            return new ValueFirstChannel();
        });
        $this->app->bind('firebase', function () {
            return new FirebaseChannel();
        });

        \Illuminate\Support\Facades\Notification::resolved(function (ChannelManager $service) {
            $service->extend('value_first', function ($app) {
                return $app->make('value_first');
            });
            $service->extend('firebase', function ($app) {
                return $app->make('firebase');
            });
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
