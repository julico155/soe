<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'visitable_id',
        'visitable_type',
        'count',
    ];

    /**
     * Get the parent visitable model (e.g. Categoria, Modulo, etc.).
     */
    public function visitable()
    {
        return $this->morphTo();
    }
}
