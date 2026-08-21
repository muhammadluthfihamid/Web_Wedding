<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasEncryptedRouteKey;
use Carbon\Carbon;

/**
 * @mixin Builder
 * @mixin Model
 */
class Order extends Model
{
    use HasFactory, HasEncryptedRouteKey;

    protected $fillable = [
        'user_id', 'rental_package_id', 'event_type', 'theme_id', 'status',
        'tanggal_mulai', 'tanggal_selesai',
        'bukti_transfer', 'catatan_user', 'catatan_admin',
        'approved_at', 'approved_by',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'approved_at'     => 'datetime',
    ];

    /* ── Relations ─────────────────────────────── */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(RentalPackage::class, 'rental_package_id');
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /* ── Helpers ───────────────────────────────── */

    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->tanggal_selesai !== null
            && $this->tanggal_selesai->isFuture();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function sisaHari(): int
    {
        if (!$this->tanggal_selesai) return 0;
        return max(0, now()->diffInDays($this->tanggal_selesai, false));
    }

    /* ── Accessors ─────────────────────────────── */

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'Menunggu Konfirmasi',
            'active'    => 'Aktif',
            'expired'   => 'Kadaluarsa',
            'rejected'  => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            default     => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'amber',
            'active'    => 'emerald',
            'expired'   => 'slate',
            'rejected'  => 'rose',
            'cancelled' => 'slate',
            default     => 'slate',
        };
    }
}
