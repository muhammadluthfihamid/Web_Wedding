<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
trait HasEncryptedRouteKey
{
    /**
     * Get the value of the model's route key (Encrypted ID).
     *
     * @return string
     */
    public function getRouteKey()
    {
        $encrypted = Crypt::encryptString((string) $this->getKey());
        return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
    }

    /**
     * Retrieve the model for a bound value (Decrypted ID).
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        // 1. Try URL-safe base64 decode + Crypt decrypt
        try {
            $base64 = strtr($value, '-_', '+/');
            $padding = strlen($base64) % 4;
            if ($padding > 0) {
                $base64 .= str_repeat('=', 4 - $padding);
            }
            $rawEncrypted = base64_decode($base64);
            if ($rawEncrypted !== false) {
                $decryptedId = Crypt::decryptString($rawEncrypted);
                return $this->where($field ?? $this->getKeyName(), $decryptedId)->first();
            }
        } catch (\Throwable $e) {
            // fallback to next step
        }

        // 2. Try direct Crypt decrypt fallback
        try {
            $decryptedId = Crypt::decryptString($value);
            return $this->where($field ?? $this->getKeyName(), $decryptedId)->first();
        } catch (\Throwable $e) {
            // fallback to numeric check
        }

        // 3. Support numeric IDs (e.g. internal admin AJAX requests or forms)
        if (is_numeric($value)) {
            return $this->where($field ?? $this->getKeyName(), $value)->first();
        }

        return null;
    }
}
