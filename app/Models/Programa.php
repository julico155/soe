<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    //
    use HasFactory;
    protected $fillable = ['sigla', 'tipo', 'titulo'];

    public function modulos()
    {
        return $this->hasMany(Modulo::class);
    }

}
