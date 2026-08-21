<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasEncryptedRouteKey;

/**
 * @mixin Builder
 * @mixin Model
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasEncryptedRouteKey;

    protected $fillable = [
        'name', 'last_name', 'email', 'password', 'role', 'slug', 'no_hp', 'event_type', 'theme_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->slug)) {
                $user->slug = \Illuminate\Support\Str::slug(($user->name ?: 'user') . '-' . \Illuminate\Support\Str::random(5));
            }
        });
    }

    public function getOrGenerateSlug(): string
    {
        if (empty($this->slug)) {
            $this->slug = \Illuminate\Support\Str::slug(($this->name ?: 'user') . '-' . \Illuminate\Support\Str::random(5));
            $this->saveQuietly();
        }
        return $this->slug;
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* ── Accessors ─────────────────────────────── */

    public function getFullNameAttribute(): string
    {
        if (is_null($this->last_name)) {
            return "{$this->name}";
        }
        return "{$this->name} {$this->last_name}";
    }

    /* ── Mutators ──────────────────────────────── */

    /**
     * @param string|null $value
     * @return void
     */
    public function setPasswordAttribute(?string $value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /* ── Role Checks ───────────────────────────── */

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasActiveRental();
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function hasRole(array|string $roles): bool
    {
        if ($this->hasActiveRental() && (is_array($roles) ? in_array('admin', $roles) : $roles === 'admin')) {
            return true;
        }
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        return $this->role === $roles;
    }

    /* ── Rental Relations & Helpers ────────────── */

    public function orders()
    {
        return $this->hasMany(Order::class)->latest();
    }

    public function activeOrder(): ?Order
    {
        return $this->orders()
            ->where('status', 'active')
            ->where('tanggal_selesai', '>=', now()->toDateString())
            ->first();
    }

    public function hasActiveRental(): bool
    {
        return $this->activeOrder() !== null;
    }

    public function hasPendingOrder(): bool
    {
        return $this->orders()->where('status', 'pending')->exists();
    }

    public function turutMengundangs()
    {
        return $this->hasMany(TurutMengundang::class)->orderBy('urutan', 'asc')->orderBy('created_at', 'asc');
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function isKhitanan(): bool
    {
        return $this->event_type === 'khitanan';
    }

    public function isWedding(): bool
    {
        return $this->event_type === 'wedding' || empty($this->event_type);
    }
}
