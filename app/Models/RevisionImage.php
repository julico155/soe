<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisionImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'revision_id',
        'image_base64',
        'filename',
        'mime_type',
    ];

    /**
     * Get the revision that owns the image.
     */
    public function revision()
    {
        return $this->belongsTo(Revision::class);
    }
}
