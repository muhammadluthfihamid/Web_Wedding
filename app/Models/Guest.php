<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEncryptedRouteKey;

/**
 * @mixin Builder
 * @mixin Model
 */
class Guest extends Model
{
    use HasEncryptedRouteKey;

    protected $fillable = ['user_id', 'nama', 'no_hp', 'keterangan', 'status_kirim'];

    protected $casts = [
        'status_kirim' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
