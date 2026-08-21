<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEncryptedRouteKey;

/**
 * @mixin Builder
 * @mixin Model
 */
class Story extends Model
{
    use HasFactory, HasEncryptedRouteKey;

    protected $fillable = [
        'user_id',
        'deskripsi',
        'judul_bertemu',
        'tgl_bertemu',
        'note_bertemu',
        'foto_bertemu',
        'judul_serius',
        'tgl_serius',
        'note_serius',
        'foto_serius',
        'judul_tunangan',
        'tgl_tunangan',
        'note_tunangan',
        'foto_tunangan'
    ];
}