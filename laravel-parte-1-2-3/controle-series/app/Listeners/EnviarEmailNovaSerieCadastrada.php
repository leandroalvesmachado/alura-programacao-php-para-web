<?php

namespace App\Listeners;

use App\Events\NovaSerie;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

use App\User;

// usando de forma sincrona
// class EnviarEmailNovaSerieCadastrada

// usando de forma assincrona
class EnviarEmailNovaSerieCadastrada implements ShouldQueue
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
        $qtdTemporadas = $event->qtdTemporadas;
        $qtdEpisodios = $event->qtdEpisodios;

        $users = User::all();

        foreach ($users as $indice => $user) {

            $multiplicador = $indice + 1;

            $email = new \App\Mail\NovaSerie(
                $nomeSerie,
                $qtdTemporadas,
                $qtdEpisodios
            );

            $email->subject = 'Nova Série Adicionada';

            // enviando email sem fila
            // Mail::to($user)->send($email);

            // envia 1 email a cada 5 segundos
            // nao viavel
            // sleep(5);

            // enviando email com fila
            // Mail::to($user)->queue($email);

            // enviando email com delay
            $delay = now()->addSecond($multiplicador * 5);
            Mail::to($user)->later($delay, $email);
        }
    }
}
