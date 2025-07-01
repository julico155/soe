<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    //

    use HasFactory;

    protected $fillable = [
        'sigla',
        'nombre',
        'porcentaje_avance',
        'programa_id',
        'docente_id'
    ];

    // Un Módulo pertenece a un Programa
    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    // Un Módulo es impartido por un Docente (Usuario)
    public function docente()
    {
        return $this->belongsTo(User::class, 'docente_id');
    }

    // Un Módulo tiene muchas Revisiones
    public function revisiones()
    {
        return $this->hasMany(Revision::class);
    }
    
}
