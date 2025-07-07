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
        'criterio1_1_cumplido',
        'criterio1_2_cumplido',
        'criterio1_3_cumplido',
        'criterio1_4_cumplido',
        'criterio2_1_cumplido',
        'criterio2_2_cumplido',
        'criterio2_3_cumplido',
        'criterio2_4_cumplido',
        'criterio3_1_cumplido',
        'criterio3_2_cumplido',
        'observacion',
        'justificacion',
    ];

    protected $casts = [
        'criterio1_1_cumplido' => 'boolean',
        'criterio1_2_cumplido' => 'boolean',
        'criterio1_3_cumplido' => 'boolean',
        'criterio1_4_cumplido' => 'boolean',
        'criterio2_1_cumplido' => 'boolean',
        'criterio2_2_cumplido' => 'boolean',
        'criterio2_3_cumplido' => 'boolean',
        'criterio2_4_cumplido' => 'boolean',
        'criterio3_1_cumplido' => 'boolean',
        'criterio3_2_cumplido' => 'boolean',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }

    /**
     * Get the images for the revision.
     */
    public function images()
    {
        return $this->hasMany(RevisionImage::class);
    }
}
