<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Events\NovaSerie;
use App\Events\SerieApagada;

use App\Listeners\EnviarEmailNovaSerieCadastrada;
use App\Listeners\LogNovaSerieCadastrada;
use App\Listeners\ExcluirCapaSerie;

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
        NovaSerie::class => [
            // pode ter mais listeners (ouvintes)
            EnviarEmailNovaSerieCadastrada::class,
            LogNovaSerieCadastrada::class,

        ],
        // SerieApagada::class => [
        //     ExcluirCapaSerie::class
        // ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
