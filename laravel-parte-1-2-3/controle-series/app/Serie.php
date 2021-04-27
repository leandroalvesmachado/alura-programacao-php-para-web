<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    // laravel automaticamente faz Serie = series
    // protected $table = 'series';

    // quando nao quiser utilizar as colunas created_at e updated_at
    public $timestamps = true;

    // quando se usa o create , se faz necessario
    protected $fillable = ['nome'];

    public function temporadas()
    {
        return $this->hasMany(Temporada::class);
    }
}
