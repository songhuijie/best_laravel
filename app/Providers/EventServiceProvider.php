<?php

namespace App\Providers;

use App\Events\Mails;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\Mail'=> [
            'App\Listeners\SendMail',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //TODO
//        Event::listen(function (Mails $event) {
//
//        });
        //TODO 通配事件
        Event::listen('event.*', function ($eventName, array $data) {
            //
        });

    }
}
