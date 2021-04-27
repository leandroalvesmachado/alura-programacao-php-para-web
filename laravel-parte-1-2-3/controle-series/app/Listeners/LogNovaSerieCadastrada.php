<?php

namespace App\Listeners;

use App\Events\NovaSerie;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

// usando de forma sincrona
// class LogNovaSerieCadastrada

// usando de forma assincrona
class LogNovaSerieCadastrada implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NovaSerie  $event
     * @return void
     */
    public function handle(NovaSerie $event)
    {
        // valor vem da classe Events/NovaSerie
        $nomeSerie = $event->nomeSerie;

        \Log::info('Série nova cadastrada '.$nomeSerie);
    }
}
