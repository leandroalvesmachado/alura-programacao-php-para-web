<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Temporada;
use App\Episodio;

class EpisodiosController extends Controller
{
    public function index(Temporada $temporada, Request $request)
    {        
        return view('episodios.index', [
            'episodios' => $temporada->episodios,
            'temporadaId' => $temporada->id,
            'mensagem' => $request->session()->get('mensagem')
        ]);
    }

    public function assistir(Request $request, Temporada $temporada)
    {
        $episodiosAssistidos = $request->episodios;

        $temporada->episodios->each(function (Episodio $episodio) use ($episodiosAssistidos) {
            // se existe return true
            $episodio->assistido = in_array(
                $episodio->id,
                $episodiosAssistidos
            );
        });

        // envia tudo que foi modificado e as suas relacoes tb modificadas (os objetos)
        $temporada->push();

        $request->session()->flash('mensagem', 'Episódios marcados como assistido');

        return redirect()->back();
    }
}
