<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    use HasFactory;
    protected $table = 'revisiones';
    protected $fillable = [
        'modulo_id',
        'p_score',
        'o_score',
        's1_score',
        'total_score', // Incluir el total en fillable
        'observacion', // <-- ¡Añade esta línea!

    ];

    protected $casts = [
        'p_score' => 'integer',
        'o_score' => 'integer',
        's1_score' => 'integer',
        'total_score' => 'integer',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
}