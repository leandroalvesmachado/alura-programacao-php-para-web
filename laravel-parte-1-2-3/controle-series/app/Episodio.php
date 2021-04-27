<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Episodio extends Model
{
    // quando se usa o create , se faz necessario
    protected $fillable = ['numero'];
    
    public function temporada()
    {
        return $this->belongsTo(Temporada::class);
    }
}
