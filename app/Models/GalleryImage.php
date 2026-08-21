<?php

namespace App\Models;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 * @mixin Model
 */
class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_id',
        'path'
    ];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
