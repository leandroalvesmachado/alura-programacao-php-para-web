<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Serie extends Model
{
    // laravel automaticamente faz Serie = series
    // protected $table = 'series';

    // quando nao quiser utilizar as colunas created_at e updated_at
    public $timestamps = true;

    // quando se usa o create , se faz necessario
    protected $fillable = ['nome', 'capa'];

    public function getCapaUrlAttribute()
    {
        // if ($this->capa) {
        //     return asset('storage/'.$this->capa);
        // }

        // return asset('storage/serie/sem-imagem.jpg');

        return $this->capa ? asset('storage/'.$this->capa) : asset('storage/serie/sem-imagem.jpg');
    }

    public function temporadas()
    {
        return $this->hasMany(Temporada::class);
    }
}
