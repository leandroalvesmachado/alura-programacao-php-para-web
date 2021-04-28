<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

// importando mais de um model
use App\{Serie, Temporada, Episodio};

use Illuminate\Support\Facades\Storage;

use App\Events\SerieApagada;

use App\Jobs\ExcluirCapaSerie;

class RemovedorDeSerie
{
    // : string informa ao metodo que ele retorna uma string
    public function removerSerie(int $serieId) : string
    {
        $nomeSerie = "";

        DB::transaction(function () use ($serieId, &$nomeSerie) {
            $serie = Serie::find($serieId);

            $serieObj = (object) $serie->toArray();
            $nomeSerie = $serie->nome;
            
            $this->removerTemporadas($serie);
            $serie->delete();

            $evento = new SerieApagada($serieObj);
            event($evento);
            
            // job
            ExcluirCapaSerie::dispatch($serieObj);
        });

        return $nomeSerie;
    }

    // private pois somente utilizado nessa classe
    private function removerTemporadas(Serie $serie) : void
    {
        $serie->temporadas->each(function (Temporada $temporada) {
            $this->removerEpisodios($temporada);
            $temporada->delete();
        });
    }

    // private pois somente utilizado nessa classe
    private function removerEpisodios(Temporada $temporada) : void
    {
        $temporada->episodios->each(function (Episodio $episodio) {
            $episodio->delete();
        });
    }
}