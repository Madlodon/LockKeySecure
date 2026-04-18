<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Service extends Model
{
    protected $fillable = ['user_id', 'name', 'url', 'account_id', 'password', 'notes', 'icon_url'];

    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    public function getPasswordAttribute(string $value): string
    {
        return Crypt::decryptString($value);
    }

    public function getIconAttribute(): string
    {
        if ($this->icon_url) {
            return $this->icon_url;
        }

        if ($this->url) {
            $host = parse_url($this->url, PHP_URL_HOST) ?? $this->url;
            return "https://logo.clearbit.com/{$host}";
        }

        return '';
    }
}
